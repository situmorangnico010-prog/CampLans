@extends('layouts.app')
@section('title', 'Pesanan Saya - CampLans')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Pesanan Saya</h1>

    <!-- Alpine.js Tab Switcher -->
    <div x-data="{ activeTab: 'payment' }" class="space-y-6">
        
        <!-- Tab Buttons (Persis Prototipe) -->
        <div class="flex bg-gray-100 p-1.5 rounded-full w-fit shadow-inner">
            <button @click="activeTab = 'payment'" 
                    :class="activeTab === 'payment' ? 'bg-teal-500 text-white shadow-md' : 'bg-transparent text-gray-500 hover:text-gray-700'"
                    class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                Menunggu Pembayaran
            </button>
            <button @click="activeTab = 'ongoing'" 
                    :class="activeTab === 'ongoing' ? 'bg-teal-500 text-white shadow-md' : 'bg-transparent text-gray-500 hover:text-gray-700'"
                    class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                Berjalan
            </button>
            <button @click="activeTab = 'history'" 
                    :class="activeTab === 'history' ? 'bg-teal-500 text-white shadow-md' : 'bg-transparent text-gray-500 hover:text-gray-700'"
                    class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                Riwayat
            </button>
        </div>

        <!-- ✅ TAB 1: Waiting for Payment -->
        <div x-show="activeTab === 'payment'" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="space-y-4">
            @forelse($rentals->where('payment_status', 'unpaid') as $rental)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex gap-5 items-start">
                <!-- Mock QR Code UI -->
                <div class="w-28 h-28 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center flex-shrink-0">
                    <svg class="w-16 h-16 text-gray-300 mb-1" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 3h7v7H3V3zm2 2v3h3V5H5zm10-2h6v6h-6V3zm2 2v2h2V5h-2zM3 15h7v6H3v-6zm2 2v2h3v-2H5zm13-2h3v3h-3v-3zm-2 2h2v2h-2v-2zm2 2h3v3h-3v-3zM13 3h2v2h-2V3zm0 4h2v2h-2V7zm4 0h2v2h-2V7z"/>
                    </svg>
                    <span class="text-[10px] font-medium text-gray-400">Scan untuk Bayar</span>
                </div>
                
                <div class="flex-1 flex flex-col justify-between h-28">
                    <div>
                        <h3 class="font-bold text-gray-900">Pesanan #{{ $rental->id }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($rental->start_date)->format('d M') }} → {{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-end justify-between">
                        <span class="text-teal-600 font-bold text-lg">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</span>
                        <form action="{{ route('rentals.pay') }}" method="POST">
                            @csrf
                            <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                            <button type="submit" class="bg-teal-500 text-white px-5 py-2 rounded-xl font-semibold hover:bg-teal-600 transition shadow-sm">Bayar Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 text-gray-400">
                Tidak ada pembayaran tertunda
            </div>
            @endforelse
        </div>

        <!-- ✅ TAB 2: Ongoing -->
        <div x-show="activeTab === 'ongoing'" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="space-y-4">
            @forelse($rentals->where('status', 'active') as $rental)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex gap-5 items-start">
                <img src="{{ $rental->details->first()?->item->image_url ?? 'https://placehold.co/100' }}" 
                     class="w-24 h-24 rounded-xl object-cover bg-gray-100 flex-shrink-0">
                
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-bold text-gray-900">{{ $rental->details->first()?->item->name ?? 'Barang Sewa' }}</h3>
                        <span class="px-2.5 py-1 bg-teal-50 text-teal-600 text-xs font-bold rounded-full">Berjalan</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Kembali pada: {{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}</p>
                    
                    <div class="flex gap-3 items-center">
                        <form action="{{ route('rentals.extend') }}" method="POST" class="flex-1 flex gap-2">
                            @csrf
                            <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                            <input type="date" name="new_end" min="{{ \Carbon\Carbon::parse($rental->end_date)->addDay()->format('Y-m-d') }}" required
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none bg-white">
                            <button type="submit" class="bg-teal-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-teal-600 transition">Perpanjang</button>
                        </form>
                        <button onclick="alert('Permintaan pengembalian dikirim ke admin!')" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Kembalikan</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 text-gray-400">
                Tidak ada pesanan berjalan
            </div>
            @endforelse
        </div>

        <!-- ✅ TAB 3: History -->
        <div x-show="activeTab === 'history'" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="space-y-4">
            @forelse($rentals->whereIn('status', ['completed', 'cancelled']) as $rental)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex gap-5 items-start">
                <img src="{{ $rental->details->first()?->item->image_url ?? 'https://placehold.co/100' }}" 
                     class="w-24 h-24 rounded-xl object-cover bg-gray-100 flex-shrink-0">
                
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-bold text-gray-900">{{ $rental->details->first()?->item->name ?? 'Barang Sewa' }}</h3>
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full capitalize">{{ $rental->status == 'completed' ? 'Selesai' : 'Dibatalkan' }}</span>
                    </div>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($rental->start_date)->format('d M') }} → {{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total: Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</p>
                    @if($rental->late_fee > 0)
                        <p class="text-xs text-red-500 mt-0.5">Denda: Rp {{ number_format($rental->late_fee, 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 text-gray-400">
                Tidak ada riwayat pesanan
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection