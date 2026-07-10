<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

/**
 * Request validation untuk registrasi customer baru.
 */
class RegisterCustomerRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                // Wajib menggunakan domain @gmail.com saja
                'regex:/^[a-zA-Z0-9._%+\-]+@gmail\.com$/',
            ],
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
        ];
    }

    /**
     * Pesan kesalahan kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Format alamat email tidak valid.',
            'email.unique'       => 'Alamat email sudah terdaftar.',
            'email.regex'        => 'Email harus menggunakan domain @gmail.com (contoh: nama@gmail.com).',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'       => 'Kata sandi minimal harus :min karakter.',
        ];
    }
}
