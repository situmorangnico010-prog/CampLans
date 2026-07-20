<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Halaman instruksi pembayaran setelah checkout.
     */
    public function show(Rental $rental)
    {
        $this->authorizeCustomer($rental);
        $rental->load('details.item.category', 'customer');

        $settings = PaymentSetting::allAsArray();

        return view('rentals.payment', compact('rental', 'settings'));
    }

    /**
     * Upload bukti pembayaran oleh customer.
     */
    public function uploadProof(Request $request, Rental $rental)
    {
        $this->authorizeCustomer($rental);

        // Hanya boleh upload jika status waiting_payment atau payment_rejected
        if (!in_array($rental->transaction_status, ['waiting_payment', 'payment_rejected'])) {
            return back()->with('error', '❌ Tidak dapat mengunggah bukti pembayaran pada status ini.');
        }

        // Cek expired
        if ($rental->is_expired && $rental->transaction_status === 'waiting_payment') {
            return back()->with('error', '⏰ Batas waktu pembayaran sudah habis. Booking dibatalkan otomatis.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'payment_method' => 'required|in:transfer_bank,qris,ewallet',
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.max'      => 'Ukuran file maksimal 5 MB.',
            'payment_method.required'=> 'Pilih metode pembayaran.',
        ]);

        // Hapus proof lama jika ada
        if ($rental->payment_proof && Storage::disk('public')->exists($rental->payment_proof)) {
            Storage::disk('public')->delete($rental->payment_proof);
        }

        // Simpan file bukti pembayaran
        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $rental->update([
            'payment_proof'      => $path,
            'proof_uploaded_at'  => now(),
            'payment_method'     => $request->payment_method,
            'transaction_status' => 'waiting_verification',
            'payment_status'     => 'pending_verification',
            'verified_by'        => null,
            'verified_at'        => null,
            'payment_note'       => null,
        ]);

        return redirect()->route('rentals.detail', $rental)
            ->with('success', '✅ Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    /**
     * Halaman detail transaksi customer.
     */
    public function detail(Rental $rental)
    {
        $this->authorizeCustomer($rental);
        $rental->load('details.item.category', 'verifiedBy');

        $settings = PaymentSetting::allAsArray();

        return view('rentals.detail', compact('rental', 'settings'));
    }

    /**
     * Halaman invoice (bisa dicetak).
     */
    public function invoice(Rental $rental)
    {
        $this->authorizeCustomer($rental);
        $rental->load('details.item.category', 'customer', 'verifiedBy');

        return view('rentals.invoice', compact('rental'));
    }

    /**
     * Pastikan hanya pemilik rental yang dapat mengakses.
     */
    private function authorizeCustomer(Rental $rental): void
    {
        if ($rental->customer_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
