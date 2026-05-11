@extends('layouts.app')
@section('title', 'Pusat Bantuan - CampLens')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pusat Bantuan</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Temukan jawaban cepat atau hubungi tim support kami</p>
        </div>

        <!-- FAQ Accordion -->
        <div class="space-y-4 mb-12" x-data="{ active: null }">
            @php
            $faqs = [
                ['q' => 'Bagaimana cara menyewa barang?', 'a' => 'Pilih barang di katalog, tentukan tanggal sewa & kembali, lalu tambahkan ke keranjang. Lakukan checkout dan tunggu konfirmasi admin. Pembayaran bisa dilakukan saat pengambilan barang.'],
                ['q' => 'Apakah bisa membatalkan pesanan?', 'a' => 'Pembatalan gratis dilakukan minimal 24 jam sebelum tanggal mulai sewa. Setelah itu, akan dikenakan biaya administrasi 10% dari total sewa.'],
                ['q' => 'Bagaimana jika terlambat mengembalikan?', 'a' => 'Terdapat grace period 2 jam. Setelah itu, dikenakan denda 50% dari harga harian per hari keterlambatan. Pastikan mengembalikan tepat waktu!'],
                ['q' => 'Apakah barang dijamin asuransi?', 'a' => 'Ya. Semua gear dilindungi asuransi kerusakan teknis & kehilangan selama masa sewa. Kerusakan akibat kelalaian pengguna akan ditagihkan sesuai ketentuan.'],
                ['q' => 'Bisa perpanjang masa sewa?', 'a' => 'Bisa. Buka menu Pesanan → klik "Extend" pada order yang aktif, pilih tanggal baru, dan sistem akan menghitung biaya tambahan secara otomatis.'],
            ];
            @endphp
            @foreach($faqs as $index => $faq)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                <button @click="active === {{ $index }} ? active = null : active = {{ $index }}" 
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $faq['q'] }}</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': active === {{ $index }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="active === {{ $index }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" 
                     class="px-5 pb-5 text-gray-600 dark:text-gray-300 text-sm leading-relaxed border-t border-gray-100 dark:border-gray-700 pt-4">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Contact Cards -->
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 text-center">Butuh Bantuan Langsung?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card rounded-2xl p-6 text-center hover:shadow-lg transition border border-gray-100 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md">
                <div class="w-14 h-14 mx-auto bg-green-50 dark:bg-green-900/30 rounded-2xl flex items-center justify-center text-2xl mb-4">💬</div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-1">WhatsApp</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Respon cepat 08.00-20.00</p>
                <a href="https://wa.me/6281234567890" class="text-teal-600 dark:text-teal-400 font-semibold text-sm hover:underline">Chat Sekarang →</a>
            </div>
            <div class="glass-card rounded-2xl p-6 text-center hover:shadow-lg transition border border-gray-100 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md">
                <div class="w-14 h-14 mx-auto bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-2xl mb-4">📧</div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-1">Email Support</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Untuk keluhan & kerjasama</p>
                <a href="mailto:support@camplans.com" class="text-teal-600 dark:text-teal-400 font-semibold text-sm hover:underline">support@camplans.com</a>
            </div>
            <div class="glass-card rounded-2xl p-6 text-center hover:shadow-lg transition border border-gray-100 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md">
                <div class="w-14 h-14 mx-auto bg-purple-50 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-2xl mb-4">📍</div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-1">Lokasi Pickup</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Jl. Kampus No. 123, Depok</p>
                <span class="text-teal-600 dark:text-teal-400 font-semibold text-sm">Buka Setiap Hari</span>
            </div>
        </div>
    </div>
</div>
@endsection