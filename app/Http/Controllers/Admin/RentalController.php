<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\PaymentSetting;
use App\Http\Requests\Admin\VerifyPaymentRequest;
use App\Http\Requests\Admin\UpdateRentalStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controller untuk mengelola transaksi sewa, verifikasi pembayaran, status rental, dan laporan keuangan toko.
 */
class RentalController extends Controller
{
    /**
     * Menampilkan daftar transaksi sewa dengan pencarian dan filter status.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function rentals(Request $request)
    {
        $query = Rental::with('customer', 'details.item')->latest();

        if ($request->filled('status')) {
            $query->where('transaction_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_sewa', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $rentals = $query->paginate(15)->withQueryString();

        $statusOptions = [
            'waiting_payment'      => 'Menunggu Pembayaran',
            'waiting_verification' => 'Menunggu Verifikasi',
            'payment_approved'     => 'Pembayaran Diterima',
            'payment_rejected'     => 'Pembayaran Ditolak',
            'processing'           => 'Sedang Diproses',
            'ready_for_pickup'     => 'Siap Diambil',
            'on_rent'              => 'Sedang Disewa',
            'returned'             => 'Dikembalikan',
            'completed'            => 'Selesai',
            'cancelled'            => 'Dibatalkan',
            'expired'              => 'Kedaluwarsa',
        ];

        return view('admin.rentals', compact('rentals', 'statusOptions'));
    }

    /**
     * Menampilkan daftar transaksi yang memerlukan verifikasi pembayaran atau telah diverifikasi.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function payments(Request $request)
    {
        $query = Rental::with('customer', 'details.item')
            ->whereIn('transaction_status', [
                'waiting_verification', 'payment_approved', 'payment_rejected'
            ])->latest();

        if ($request->filled('status')) {
            $query->where('transaction_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_sewa', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $rentals = $query->paginate(15)->withQueryString();

        return view('admin.payments', compact('rentals'));
    }

    /**
     * Menampilkan halaman detail pembayaran sewa untuk verifikasi admin.
     *
     * @param Rental $rental
     * @return \Illuminate\View\View
     */
    public function paymentDetail(Rental $rental)
    {
        $rental->load('customer', 'details.item.category', 'verifiedBy');
        return view('admin.payment-detail', compact('rental'));
    }

    /**
     * Menerima atau menolak bukti pembayaran sewa customer.
     *
     * @param VerifyPaymentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyPayment(VerifyPaymentRequest $request)
    {
        $rental = Rental::findOrFail($request->rental_id);

        if ($rental->transaction_status !== 'waiting_verification') {
            return back()->with('error', '❌ Status tidak valid untuk diverifikasi.');
        }

        if ($request->action === 'approve') {
            $rental->update([
                'transaction_status' => 'payment_approved',
                'payment_status'     => 'paid',
                'payment_note'       => $request->note,
                'verified_by'        => auth()->id(),
                'verified_at'        => now(),
            ]);
            return back()->with('success', '✅ Pembayaran diterima. Status: Payment Approved.');
        }

        // Jika ditolak, set ulang batas kadaluwarsa unggah bukti bayar
        $paymentHours = (int) PaymentSetting::get('payment_hours', '24');
        $rental->update([
            'transaction_status' => 'payment_rejected',
            'payment_status'     => 'rejected',
            'payment_note'       => $request->note,
            'verified_by'        => auth()->id(),
            'verified_at'        => now(),
            'expired_at'         => now()->addHours($paymentHours),
        ]);

        return back()->with('success', '🔄 Pembayaran ditolak. Customer dapat upload ulang bukti.');
    }

    /**
     * Memperbarui alur status rental (processing -> ready_for_pickup -> on_rent -> returned -> completed).
     *
     * @param UpdateRentalStatusRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRentalStatus(UpdateRentalStatusRequest $request)
    {
        $rental = Rental::findOrFail($request->rental_id);

        $allowedTransitions = [
            'payment_approved' => ['processing'],
            'processing'       => ['ready_for_pickup'],
            'ready_for_pickup' => ['on_rent'],
            'on_rent'          => ['returned'],
            'returned'         => ['completed'],
        ];

        $currentStatus = $rental->transaction_status;
        $allowed = $allowedTransitions[$currentStatus] ?? [];

        if (!in_array($request->new_status, $allowed)) {
            return back()->with('error', "❌ Tidak dapat mengubah status dari '{$currentStatus}' ke '{$request->new_status}'.");
        }

        $updateData = ['transaction_status' => $request->new_status];

        // Jika barang dikembalikan, tandai waktu kembali & hitung denda keterlambatan jika ada
        if ($request->new_status === 'returned') {
            $updateData['returned_at'] = now();
            $updateData['actual_return_date'] = now()->toDateString();

            // Hitung denda berdasarkan selisih hari kalender
            $lateFee = $this->calculateLateFee($rental->end_date, now());
            if ($lateFee > 0) {
                // Gunakan nilai DB asli (bukan accessor dinamis) untuk menghindari double-add
                $baseAmount = $rental->getRawOriginal('total_amount') ?? $rental->total_amount;
                $updateData['late_fee']     = $lateFee;
                $updateData['total_amount'] = $baseAmount + $lateFee;
            }
        }

        // Jika completed, kembalikan stok dan selesaikan transaksi
        if ($request->new_status === 'completed') {
            $updateData['status'] = 'completed';
            DB::transaction(function () use ($rental, $updateData) {
                // Kembalikan stok item ke inventori
                foreach ($rental->details as $detail) {
                    $detail->item->increment('stock', $detail->quantity);
                }
                $rental->update($updateData);
            });
            return back()->with('success', '🏁 Transaksi selesai. Stok telah dikembalikan.');
        }

        if ($request->new_status === 'on_rent') {
            $updateData['status'] = 'active';
        }

        $rental->update($updateData);
        return back()->with('success', "✅ Status berhasil diubah ke: {$rental->fresh()->status_label}");
    }

    /**
     * Membatalkan pemesanan sewa oleh admin dan mengembalikan alokasi stok.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelRental(Request $request)
    {
        $request->validate(['rental_id' => 'required|exists:rentals,id']);

        $rental = Rental::with('details.item')->findOrFail($request->rental_id);

        if (in_array($rental->transaction_status, ['completed', 'cancelled', 'expired', 'returned'])) {
            return back()->with('error', '❌ Transaksi tidak dapat dibatalkan pada status ini.');
        }

        DB::transaction(function () use ($rental) {
            // Kembalikan stok item ke inventori
            foreach ($rental->details as $detail) {
                $detail->item->increment('stock', $detail->quantity);
            }

            $rental->update([
                'transaction_status' => 'cancelled',
                'status'             => 'cancelled',
                'payment_status'     => 'unpaid',
            ]);
        });

        return back()->with('success', '🚫 Rental berhasil dibatalkan.');
    }

    /**
     * Menampilkan laporan keuangan / pendapatan dan penarikan dana toko.
     *
     * @return \Illuminate\View\View
     */
    public function withdraw()
    {
        $paidRentals = Rental::where('payment_status', 'paid')->get();
        $totalIncome = $paidRentals->sum('total_amount');
        $now = Carbon::now();

        $summaries = [
            'today'      => $paidRentals->filter(fn($r) => Carbon::parse($r->updated_at)->isToday())->sum('total_amount'),
            'this_week'  => $paidRentals->filter(fn($r) => Carbon::parse($r->updated_at)->isSameWeek($now))->sum('total_amount'),
            'this_month' => $paidRentals->filter(fn($r) => Carbon::parse($r->updated_at)->isSameMonth($now))->sum('total_amount'),
            'this_year'  => $paidRentals->filter(fn($r) => Carbon::parse($r->updated_at)->isSameYear($now))->sum('total_amount'),
            'history'    => $paidRentals->groupBy(fn($r) => Carbon::parse($r->updated_at)->format('Y-m-d'))
                                        ->map(fn($group) => $group->sum('total_amount'))
                                        ->sortKeysDesc()
                                        ->take(10),
        ];

        return view('admin.withdraw', compact('totalIncome', 'summaries'));
    }

    /**
     * Menghitung denda keterlambatan pengembalian barang.
     * Menggunakan konfigurasi penalty_per_day dari payment_settings.
     *
     * @param mixed $endDate Tanggal akhir sewa
     * @param mixed $returnDate Tanggal pengembalian aktual
     * @return float Jumlah denda
     */
    private function calculateLateFee($endDate, $returnDate): float
    {
        $end = Carbon::parse($endDate)->endOfDay();
        $ret = Carbon::parse($returnDate);

        if ($ret <= $end) return 0;

        // Selisih hari kalender (minimal 1 hari)
        $lateDays = max(1, (int) $ret->startOfDay()->diffInDays($end->startOfDay()));

        // Ambil tarif denda per hari dari konfigurasi
        $penaltyPerDay = (float) PaymentSetting::get('penalty_per_day', '50000');

        return $lateDays * $penaltyPerDay;
    }
}
