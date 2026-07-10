<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation untuk mengganti status transaksi sewa.
 */
class UpdateRentalStatusRequest extends FormRequest
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
            'rental_id'  => ['required', 'exists:rentals,id'],
            'new_status' => ['required', 'string'],
        ];
    }

    /**
     * Pesan kesalahan kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'rental_id.required' => 'ID rental wajib disertakan.',
            'rental_id.exists' => 'Data rental tidak ditemukan.',
            'new_status.required' => 'Status baru wajib ditentukan.',
        ];
    }
}
