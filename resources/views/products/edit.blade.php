@extends('layouts.app')

@section('content')

<form action="/products/{{ $product->id }}" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $product->name }}" class="w-full mb-3 p-2 border">
    <textarea name="description" class="w-full mb-3 p-2 border">{{ $product->description }}</textarea>
    <input type="number" name="price" value="{{ $product->price }}" class="w-full mb-3 p-2 border">

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
</form>

@endsection