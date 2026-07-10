<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation untuk menyimpan pengaturan pembayaran admin.
 */
class SavePaymentSettingsRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Aturan validasi yang berlaku untuk request ini.
     */
    public function rules(): array
    {
        return [
            'bank_name'       => ['required', 'string', 'max:100'],
            'account_number'  => ['required', 'string', 'max:50'],
            'account_name'    => ['required', 'string', 'max:150'],
            'ewallet_name'    => ['nullable', 'string', 'max:100'],
            'ewallet_number'  => ['nullable', 'string', 'max:50'],
            'payment_hours'   => ['required', 'integer', 'min:1', 'max:72'],
            'penalty_per_day' => ['required', 'numeric', 'min:0'],
            'qris_image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    /**
     * Pesan kesalahan kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'bank_name.required' => 'Nama bank wajib diisi.',
            'account_number.required' => 'Nomor rekening bank wajib diisi.',
            'account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'payment_hours.required' => 'Batas waktu pembayaran wajib ditentukan.',
            'payment_hours.integer' => 'Batas waktu pembayaran harus berupa angka jam.',
            'payment_hours.min' => 'Batas waktu pembayaran minimal 1 jam.',
            'payment_hours.max' => 'Batas waktu pembayaran maksimal 72 jam.',
            'penalty_per_day.required' => 'Denda per hari wajib diisi.',
            'penalty_per_day.numeric' => 'Denda per hari harus berupa angka.',
            'penalty_per_day.min' => 'Denda per hari tidak boleh negatif.',
            'qris_image.image' => 'File QRIS harus berupa gambar.',
            'qris_image.mimes' => 'Format gambar QRIS harus jpeg, png, jpg, atau webp.',
            'qris_image.max' => 'Ukuran QRIS maksimal adalah 2 MB.',
        ];
    }
}
