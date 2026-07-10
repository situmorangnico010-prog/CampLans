<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation untuk memperbarui data barang.
 */
class UpdateProductRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:items,id'],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // max 5MB
        ];
    }

    /**
     * Pesan kesalahan kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'id.required' => 'ID barang tidak ditemukan.',
            'id.exists' => 'Barang yang ingin diperbarui tidak terdaftar.',
            'name.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'daily_rate.required' => 'Harga rental harian wajib diisi.',
            'daily_rate.numeric' => 'Harga rental harus berupa angka.',
            'daily_rate.min' => 'Harga rental minimal adalah 0.',
            'stock.required' => 'Jumlah stok barang wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok minimal adalah 1.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal adalah 5 MB.',
        ];
    }
}
