@extends('layouts.app')

@section('content')

<form action="/products" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    @csrf

    <input type="text" name="name" placeholder="Nama Produk" class="w-full mb-3 p-2 border rounded">
    <textarea name="description" placeholder="Deskripsi" class="w-full mb-3 p-2 border"></textarea>
    <input type="number" name="price" placeholder="Harga" class="w-full mb-3 p-2 border rounded">

    <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
</form>

@endsection