{{-- 
Halaman Checkout (Konfirmasi Booking)
Menampilkan detail item yang akan disewa, durasi sewa, jumlah unit, dan kalkulasi subtotal.
Pelanggan dapat meninjau data pemesanan sebelum melakukan submit booking secara final.
--}}
@extends('layouts.app')
@section('title', 'Checkout Sewa - CampLens')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('cart.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 flex items-center gap-1">
            ← Kembali ke Keranjang
        </a>
        <h1 class="text-3xl font-bold mt-3 mb-2 tracking-tight text-gray-900 dark:text-white">🛒 Konfirmasi Booking</h1>
        <p class="text-gray-500 dark:text-gray-400">Selesaikan pemesanan Anda untuk jadwal sewa terpilih.</p>
    </div>

    @if($stockWarning)
        <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-2xl p-5 mb-8 text-red-700 dark:text-red-300 flex gap-3 items-start">
            <span class="text-xl">⚠️</span>
            <div>
                <h4 class="font-bold">Stok Tidak Tersedia</h4>
                <p class="text-sm mt-1">{{ $stockWarning }} Silakan ubah jadwal sewa atau hapus item ini dari keranjang.</p>
            </div>
        </div>
    @endif

    {{-- Main Checkout Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left: Item and Schedule Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Selected Item Details Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Rincian Alat</h3>
                
                <div class="flex gap-4 items-start pb-6 border-b border-gray-100 dark:border-gray-700">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" 
                         class="w-20 h-20 rounded-xl object-cover bg-gray-100 dark:bg-gray-700 flex-shrink-0"
                         onerror="this.src='https://placehold.co/80x80/f5f5f7/86868b?text=IMG'">
                    <div class="flex-1 min-w-0">
                        <span class="inline-block bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 text-xs px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wide mb-1">
                            {{ $item->category->name }}
                        </span>
                        <h4 class="font-bold text-gray-900 dark:text-white truncate">{{ $item->name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            Rp{{ number_format($item->daily_rate, 0, ',', '.') }} / hari
                        </p>
                    </div>
                </div>

                <div class="pt-6 space-y-4">
                    <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200">Jadwal Penyewaan</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3.5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Tanggal Mulai</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mt-1">
                                {{ \Carbon\Carbon::parse($cartItem['start_date'])->format('d M Y') }}
                            </p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3.5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Tanggal Kembali</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mt-1">
                                {{ \Carbon\Carbon::parse($cartItem['end_date'])->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center bg-teal-50 dark:bg-teal-900/20 p-4 rounded-xl border border-teal-100 dark:border-teal-800/50 mt-4">
                        <span class="text-sm font-semibold text-teal-800 dark:text-teal-300">Durasi Sewa:</span>
                        <span class="bg-teal-600 text-white px-3 py-1 rounded-lg text-xs font-bold">
                            ⏱️ {{ $cartItem['duration'] }} Hari
                        </span>
                    </div>

                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Jumlah Unit:</span>
                        <span class="font-bold text-gray-900 dark:text-white">
                            {{ $cartItem['quantity'] }} Unit
                        </span>
                    </div>

                    @if(!empty($cartItem['notes']))
                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Catatan Sewa</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $cartItem['notes'] }}"</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Checkout Payment Form & Summary --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Ringkasan Biaya</h3>
                
                <div class="space-y-3 pb-5 border-b border-gray-100 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex justify-between">
                        <span>Biaya Harian</span>
                        <span>Rp{{ number_format($item->daily_rate, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Durasi</span>
                        <span>{{ $cartItem['duration'] }} Hari</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jumlah</span>
                        <span>{{ $cartItem['quantity'] }} Unit</span>
                    </div>
                </div>

                <div class="flex justify-between items-center py-5 border-b border-gray-100 dark:border-gray-700 mb-6">
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Total Pembayaran</span>
                    <span class="text-2xl font-black text-teal-600 dark:text-teal-400">
                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Payment Form --}}
                <form method="POST" action="{{ route('cart.checkout', $cart_id) }}" x-data="{ method: '' }">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Pilih Metode Pembayaran</label>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mb-3 font-semibold">{{ $message }}</p>
                        @enderror

                        <div class="space-y-3">
                            {{-- Bank Transfer --}}
                            <label class="cursor-pointer block">
                                <input type="radio" name="payment_method" value="transfer_bank" class="peer sr-only" x-model="method" @if($stockWarning) disabled @endif>
                                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl p-3 flex items-center justify-between peer-checked:border-teal-500 peer-checked:bg-teal-50/50 dark:peer-checked:bg-teal-900/10 peer-disabled:opacity-50 hover:border-teal-300 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="text-xl">🏦</div>
                                        <div class="text-left">
                                            <div class="font-semibold text-sm text-gray-800 dark:text-gray-200">Transfer Bank</div>
                                            <div class="text-[10px] text-gray-400">BCA, Mandiri, BNI</div>
                                        </div>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-teal-500">
                                        <div class="w-2 h-2 rounded-full bg-teal-500" x-show="method === 'transfer_bank'" style="display: none;"></div>
                                    </div>
                                </div>
                            </label>

                            {{-- QRIS --}}
                            <label class="cursor-pointer block">
                                <input type="radio" name="payment_method" value="qris" class="peer sr-only" x-model="method" @if($stockWarning) disabled @endif>
                                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl p-3 flex items-center justify-between peer-checked:border-teal-500 peer-checked:bg-teal-50/50 dark:peer-checked:bg-teal-900/10 peer-disabled:opacity-50 hover:border-teal-300 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="text-xl">📸</div>
                                        <div class="text-left">
                                            <div class="font-semibold text-sm text-gray-800 dark:text-gray-200">QRIS Manual</div>
                                            <div class="text-[10px] text-gray-400">Scan QR Code</div>
                                        </div>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-teal-500">
                                        <div class="w-2 h-2 rounded-full bg-teal-500" x-show="method === 'qris'" style="display: none;"></div>
                                    </div>
                                </div>
                            </label>

                            {{-- E-Wallet --}}
                            <label class="cursor-pointer block">
                                <input type="radio" name="payment_method" value="ewallet" class="peer sr-only" x-model="method" @if($stockWarning) disabled @endif>
                                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl p-3 flex items-center justify-between peer-checked:border-teal-500 peer-checked:bg-teal-50/50 dark:peer-checked:bg-teal-900/10 peer-disabled:opacity-50 hover:border-teal-300 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="text-xl">📱</div>
                                        <div class="text-left">
                                            <div class="font-semibold text-sm text-gray-800 dark:text-gray-200">E-Wallet</div>
                                            <div class="text-[10px] text-gray-400">GoPay, OVO, Dana</div>
                                        </div>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-teal-500">
                                        <div class="w-2 h-2 rounded-full bg-teal-500" x-show="method === 'ewallet'" style="display: none;"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" 
                            @if($stockWarning) disabled @endif
                            class="w-full bg-teal-600 text-white py-3.5 rounded-xl font-bold hover:bg-teal-700 transition shadow-sm hover:shadow-md flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        ✅ Konfirmasi & Booking
                    </button>
                    
                    <p class="text-[10px] text-gray-400 text-center mt-4">
                        *Anda memiliki waktu <strong>{{ $paymentHours ?? 24 }} jam</strong> setelah pemesanan untuk membayar dan mengunggah bukti pembayaran.
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
