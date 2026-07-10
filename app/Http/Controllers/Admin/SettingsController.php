<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Http\Requests\Admin\SavePaymentSettingsRequest;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk mengelola pengaturan profil admin dan konfigurasi detail pembayaran.
 */
class SettingsController extends Controller
{
    /**
     * Menampilkan formulir pengaturan metode pembayaran bank dan QRIS toko.
     *
     * @return \Illuminate\View\View
     */
    public function paymentSettings()
    {
        $settings = PaymentSetting::allAsArray();
        return view('admin.payment-settings', compact('settings'));
    }

    /**
     * Menyimpan pembaruan konfigurasi pembayaran bank/QRIS.
     *
     * @param SavePaymentSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function savePaymentSettings(SavePaymentSettingsRequest $request)
    {
        $fields = ['bank_name', 'account_number', 'account_name', 'ewallet_name', 'ewallet_number', 'payment_hours', 'penalty_per_day'];
        
        foreach ($fields as $field) {
            PaymentSetting::set($field, $request->input($field));
        }

        // Jika terdapat unggahan file QRIS baru
        if ($request->hasFile('qris_image')) {
            $old = PaymentSetting::get('qris_image');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('qris_image')->store('qris', 'public');
            PaymentSetting::set('qris_image', $path);
        }

        return back()->with('success', '✅ Pengaturan pembayaran berhasil disimpan.');
    }

    /**
     * Menampilkan halaman pengaturan profil admin.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Memperbarui profil identitas dan kata sandi admin.
     *
     * @param UpdateSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(UpdateSettingsRequest $request)
    {
        $user = auth()->user();

        $user->name  = $request->name;
        $user->email = $request->email;

        // Jika kata sandi diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', '✅ Profil admin berhasil diperbarui');
    }
}
