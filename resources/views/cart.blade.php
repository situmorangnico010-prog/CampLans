@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-4">Keranjang Rental</h2>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">Produk</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @php $total = 0; @endphp

        @foreach($cart as $id => $item)
        @php 
            $subtotal = $item['price'] * $item['qty']; 
            $total += $subtotal; 
        @endphp

        <tr class="border-t">
            <td class="p-2">{{ $item['name'] }}</td>
            <td>Rp {{ $item['price'] }}</td>
            <td>{{ $item['qty'] }}</td>
            <td>Rp {{ $subtotal }}</td>
            <td>
                <a href="/cart/remove/{{ $id }}" class="bg-red-500 text-white px-2 py-1 rounded">
                    Hapus
                </a>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

<div class="mt-4 text-right">
    <h3 class="text-xl font-bold">Total: Rp {{ $total }}</h3>
</div>

@endsection