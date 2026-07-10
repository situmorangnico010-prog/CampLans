<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation untuk verifikasi pembayaran sewa.
 */
class VerifyPaymentRequest extends FormRequest
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
            'rental_id' => ['required', 'exists:rentals,id'],
            'action'    => ['required', 'in:approve,reject'],
            'note'      => ['nullable', 'string', 'max:500'],
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
            'action.required' => 'Tindakan persetujuan/penolakan wajib dipilih.',
            'action.in' => 'Tindakan yang dipilih tidak valid.',
            'note.max' => 'Catatan verifikasi maksimal :max karakter.',
        ];
    }
}
