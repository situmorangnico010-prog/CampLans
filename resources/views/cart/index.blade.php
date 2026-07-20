{{-- 
Halaman Keranjang Belanja (Cart Index)
Menampilkan daftar semua item/peralatan camping yang telah dimasukkan oleh pelanggan ke dalam keranjang belanja.
Setiap item dalam keranjang dapat dihapus atau langsung dilanjutkan ke halaman checkout.
--}}
@extends('layouts.app')
@section('title', 'Keranjang Belanja - CampLens')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-2 tracking-tight">🛒 Keranjang Belanja</h1>
    <p class="text-gray-500 mb-8">Item yang disimpan sementara. Silakan lakukan checkout secara terpisah untuk setiap jadwal sewa.</p>

    @if(empty($cartItems))
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center shadow-sm">
            <div class="text-6xl mb-4">📦</div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Keranjang masih kosong</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Pilih peralatan favorit Anda dan mulai petualangan!</p>
            <a href="{{ route('items.index') }}" class="inline-block bg-teal-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-teal-700 transition shadow-sm">
                Jelajahi Katalog
            </a>
        </div>
    @else
        {{-- List of Cart Items --}}
        <div class="space-y-6">
            @foreach($cartItems as $cartItem)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition p-5 flex flex-col md:flex-row gap-5 items-start md:items-center">
                    {{-- Product Image --}}
                    <img src="{{ $cartItem['item']->image_url }}" alt="{{ $cartItem['item']->name }}" 
                         class="w-24 h-24 rounded-xl object-cover bg-gray-100 dark:bg-gray-700 flex-shrink-0"
                         onerror="this.src='https://placehold.co/100x100/f5f5f7/86868b?text=IMG'">
                    
                    {{-- Item Description --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">
                                {{ $cartItem['item']->category->name }}
                            </span>
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs px-2.5 py-1 rounded-full font-semibold">
                                x{{ $cartItem['quantity'] }} Unit
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate mb-1">
                            {{ $cartItem['item']->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                            Rp{{ number_format($cartItem['item']->daily_rate, 0, ',', '.') }} / hari
                        </p>
                        
                        {{-- Schedule Details --}}
                        <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-sm text-gray-600 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 pt-2 mt-2">
                            <span class="flex items-center gap-1">
                                📅 Mulai: <strong>{{ $cartItem['start_date']->format('d M Y') }}</strong>
                            </span>
                            <span class="flex items-center gap-1">
                                🔙 Selesai: <strong>{{ $cartItem['end_date']->format('d M Y') }}</strong>
                            </span>
                            <span class="bg-teal-50 dark:bg-teal-900/30 text-teal-800 dark:text-teal-300 px-2 py-0.5 rounded text-xs font-bold">
                                ⏱️ {{ $cartItem['duration'] }} Hari
                            </span>
                        </div>

                        {{-- Catatan --}}
                        @if(!empty($cartItem['notes']))
                            <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-xs text-gray-500 dark:text-gray-400 italic">
                                📝 Catatan: "{{ $cartItem['notes'] }}"
                            </div>
                        @endif
                    </div>

                    {{-- Price & Actions --}}
                    <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto border-t md:border-t-0 border-gray-100 dark:border-gray-700 pt-4 md:pt-0 gap-4">
                        <div class="text-left md:text-right">
                            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">Subtotal</p>
                            <p class="font-black text-teal-600 dark:text-teal-400 text-xl">
                                Rp{{ number_format($cartItem['subtotal'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            {{-- Remove Button --}}
                            <form method="POST" action="{{ route('cart.remove', $cartItem['id']) }}">
                                @csrf
                                <button type="submit" class="text-red-400 hover:text-red-600 dark:hover:text-red-300 transition p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-red-50 dark:hover:bg-red-950/20" title="Hapus dari keranjang">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            {{-- Checkout Button --}}
                            <a href="{{ route('cart.checkout.page', $cartItem['id']) }}" 
                               class="bg-teal-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-teal-700 transition shadow-sm hover:shadow-md flex items-center gap-1.5">
                                Checkout →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection