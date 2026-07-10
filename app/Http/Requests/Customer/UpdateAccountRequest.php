<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Request validation untuk pembaruan profil akun customer.
 */
class UpdateAccountRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Aturan validasi yang berlaku untuk request ini.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', Password::min(8)],
        ];
    }

    /**
     * Pesan kesalahan kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap terlalu panjang.',
            'password.min' => 'Kata sandi minimal harus :min karakter.',
        ];
    }
}
