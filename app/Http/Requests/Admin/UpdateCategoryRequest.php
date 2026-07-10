<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation untuk memperbarui nama kategori.
 */
class UpdateCategoryRequest extends FormRequest
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
            'id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $this->id],
        ];
    }

    /**
     * Pesan kesalahan kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'id.required' => 'ID kategori tidak ditemukan.',
            'id.exists' => 'Kategori yang ingin diubah tidak terdaftar.',
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah terdaftar.',
            'name.max' => 'Nama kategori maksimal adalah 255 karakter.',
        ];
    }
}
