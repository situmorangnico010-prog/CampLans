@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manajemen Produk</h2>
        <a href="/products/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Produk
        </a>
    </div>

    <!-- Alert -->
    @if(session('success'))
    <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="w-full text-sm text-left">

            <!-- Head -->
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3">Nama Produk</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3 text-center">Harga</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <!-- Body -->
            <tbody>
                @forelse($products as $index => $p)
                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3 text-center">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-4 py-3 font-semibold">
                        {{ $p->name }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $p->description }}
                    </td>

                    <td class="px-4 py-3 text-center text-blue-600 font-bold">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </td>

                    <td class="px-4 py-3 text-center space-x-2">

                        <!-- Sewa -->
                        <form action="/cart/add/{{ $p->id }}" method="POST" class="inline">
                            @csrf
                            <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                Sewa
                            </button>
                        </form>

                        <!-- Edit -->
                        <a href="/products/{{ $p->id }}/edit" 
                           class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">
                            Edit
                        </a>

                        <!-- Delete -->
                        <form action="/products/{{ $p->id }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus produk?')" 
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </form>

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-500">
                        Belum ada produk
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection