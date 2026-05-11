@extends('layouts.app')
@section('title', 'Keranjang Sewa - CampLans')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-8 tracking-tight">🛒 Keranjang Sewa</h1>

    @if(empty($cart['items']))
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">
            <div class="text-6xl mb-4">📦</div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Keranjang masih kosong</h2>
            <p class="text-gray-500 mb-6">Pilih peralatan favorit Anda dan mulai petualangan!</p>
            <a href="{{ route('items.index') }}" class="inline-block bg-teal-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-teal-700 transition shadow-sm">
                Jelajahi Katalog
            </a>
        </div>
    @else
        <!-- Date & Duration Badge -->
        <div class="bg-teal-50 border border-teal-200 rounded-2xl p-5 mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-wrap gap-3">
                <span class="bg-white px-4 py-2 rounded-lg border border-teal-200 text-sm font-medium text-teal-800">
                    📅 Mulai: {{ \Carbon\Carbon::parse($cart['start'])->format('d M Y') }}
                </span>
                <span class="bg-white px-4 py-2 rounded-lg border border-teal-200 text-sm font-medium text-teal-800">
                    🔙 Kembali: {{ \Carbon\Carbon::parse($cart['end'])->format('d M Y') }}
                </span>
            </div>
            <span class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-bold">
                ⏱️ {{ $days }} Hari Sewa
            </span>
        </div>

        <!-- Cart Items -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm mb-6">
            @foreach($items as $item)
                @php $qty = $cart['items'][$item->id] ?? 1; $subtotal = $days * $item->daily_rate * $qty; @endphp
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-20 h-20 rounded-xl object-cover bg-gray-100 flex-shrink-0"
                         onerror="this.src='https://placehold.co/80x80/f5f5f7/86868b?text=IMG'">
                    
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $item->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $item->category->name }} • Rp{{ number_format($item->daily_rate, 0, ',', '.') }}/hari</p>
                    </div>
                    
                    <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">x{{ $qty }}</span>
                        <span class="font-bold text-teal-600 text-lg min-w-[100px] text-right">
                            Rp{{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Checkout Section -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-100">
                <span class="text-lg font-medium text-gray-700">Total Estimasi</span>
                <span class="text-3xl font-bold text-teal-600">Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <form method="POST" action="{{ route('cart.checkout') }}" class="flex flex-col sm:flex-row gap-4">
                @csrf
                <a href="{{ route('items.index') }}" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3.5 rounded-xl font-medium hover:bg-gray-200 transition text-center">
                    ← Tambah Barang
                </a>
                <button type="submit" class="flex-1 bg-teal-600 text-white px-6 py-3.5 rounded-xl font-semibold hover:bg-teal-700 transition shadow-sm hover:shadow-md">
                    ✅ Konfirmasi & Booking
                </button>
            </form>
            <p class="text-xs text-gray-400 text-center mt-4">
                *Harga final dikonfirmasi saat pengambilan barang. Denda keterlambatan berlaku sesuai ketentuan.
            </p>
        </div>
    @endif
</div>
@endsection