<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Riwayat & daftar semua rental customer (dengan tab status baru).
     */
    public function index()
    {
        $rentals = auth()->user()->rentals()
            ->with('details.item.category')
            ->latest()
            ->get();

        return view('rentals.index', compact('rentals'));
    }

    /**
     * Perpanjang masa sewa (hanya untuk status on_rent).
     * Membuat rental BARU untuk periode perpanjangan dan mengarahkan ke halaman pembayaran.
     */
    public function extend(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'new_end'   => 'required|date|after:today'
        ]);

        $rental = Rental::where('id', $request->rental_id)
            ->where('customer_id', auth()->id())
            ->where('transaction_status', 'on_rent')
            ->firstOrFail();

        $newEnd = Carbon::parse($request->new_end);
        if ($newEnd->lte(Carbon::parse($rental->end_date))) {
            return back()->with('error', '⚠️ Tanggal baru harus setelah tanggal kembali saat ini.');
        }

        // Tanggal mulai perpanjangan = hari setelah end_date rental aktif
        $extStart = Carbon::parse($rental->end_date)->addDay();

        // Cek konflik jadwal untuk periode perpanjangan (tidak termasuk rental yang sedang aktif)
        $itemIds = $rental->details->pluck('item_id');
        foreach ($itemIds as $itemId) {
            if ($this->checkConflict($itemId, $extStart->toDateString(), $newEnd->toDateString(), $rental->id)) {
                return back()->with('error', '⚠️ Salah satu barang sudah dipesan orang lain pada tanggal tersebut.');
            }
        }

        // Hitung biaya perpanjangan
        $days  = $extStart->diffInDays($newEnd) + 1;
        $extra = 0;
        foreach ($rental->details as $detail) {
            $extra += $days * $detail->item->daily_rate * $detail->quantity;
        }

        DB::beginTransaction();
        try {
            // Ambil jam batas bayar dari settings (default 24 jam)
            $paymentHours = (int) (\App\Models\PaymentSetting::get('payment_hours', '24'));

            // Buat rental BARU untuk periode perpanjangan
            $newRental = Rental::create([
                'customer_id'        => auth()->id(),
                'start_date'         => $extStart->toDateString(),
                'end_date'           => $newEnd->toDateString(),
                'total_amount'       => 0,
                'status'             => 'pending',
                'transaction_status' => 'waiting_payment',
                'payment_status'     => 'unpaid',
                'payment_method'     => $rental->payment_method ?? 'transfer_bank',
                'expired_at'         => now()->addHours($paymentHours),
            ]);

            // Salin detail item dari rental lama ke rental perpanjangan
            $total = 0;
            foreach ($rental->details as $detail) {
                $subtotal = $days * $detail->item->daily_rate * $detail->quantity;
                $total   += $subtotal;

                RentalDetail::create([
                    'rental_id' => $newRental->id,
                    'item_id'   => $detail->item_id,
                    'quantity'  => $detail->quantity,
                    'subtotal'  => $subtotal,
                ]);
            }

            $newRental->update(['total_amount' => $total]);

            DB::commit();

            return redirect()->route('rentals.payment', $newRental)
                ->with('success', "📅 Perpanjangan berhasil! +{$days} hari — Rp" . number_format($extra, 0, ',', '.') . ". Silakan selesaikan pembayaran.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Gagal membuat perpanjangan sewa: ' . $e->getMessage());
        }
    }

    private function checkConflict($itemId, $start, $end, $excludeRentalId)
    {
        return RentalDetail::where('item_id', $itemId)
            ->whereHas('rental', function ($q) use ($start, $end, $excludeRentalId) {
                $q->whereNotIn('transaction_status', ['cancelled', 'completed', 'expired']);
                if ($excludeRentalId) {
                    $q->where('id', '!=', $excludeRentalId);
                }
                $q->where('start_date', '<=', $end)
                  ->where('end_date', '>=', $start);
            })->exists();
    }
}