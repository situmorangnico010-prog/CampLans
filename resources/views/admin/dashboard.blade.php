@extends('layouts.admin')
@section('title', 'Dashboard Admin - CampLens')

@section('content')
<!-- Welcome Banner -->
<div class="banner-container">
    <img src="{{ asset('images/admin_banner.png') }}" alt="Admin Banner" class="banner-img" onerror="this.src='https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'">
    <div class="banner-overlay">
        <div class="text-white">
            <h1 class="text-4xl font-black mb-2 tracking-tight">Selamat Datang, Admin!</h1>
            <p class="text-lg opacity-90 max-w-md font-medium">Kelola operasional CampLens dengan mudah melalui dashboard yang terintegrasi.</p>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.items') }}" class="px-6 py-2.5 bg-white text-teal-700 rounded-full font-bold text-sm hover:scale-105 transition-transform">Kelola Barang</a>
                <div class="px-6 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-full font-bold text-sm">
                    {{ date('d F Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Stat Item -->
    <div class="dashboard-card">
        <div class="flex justify-between items-start mb-6">
            <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <a href="{{ route('admin.items') }}" class="text-xs font-bold text-teal-600 hover:underline">Kelola →</a>
        </div>
        <h3 class="stat-label">Peralatan Terdaftar</h3>
        <div class="space-y-4">
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-black text-slate-800">{{ $stats['manage_item']['listed_items']['cameras'] + $stats['manage_item']['listed_items']['camps'] }}</p>
                    <p class="text-xs font-bold text-slate-400">Total Stok Unit</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-teal-600">{{ $stats['manage_item']['listed_series']['cameras'] + $stats['manage_item']['listed_series']['camps'] }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Seri Barang</p>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-50 grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-sm font-black text-slate-700">{{ $stats['manage_item']['listed_items']['cameras'] }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Kamera</p>
                </div>
                <div>
                    <p class="text-sm font-black text-slate-700">{{ $stats['manage_item']['listed_items']['camps'] }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Camping</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Category -->
    <div class="dashboard-card">
        <div class="flex justify-between items-start mb-6">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <a href="{{ route('admin.categories') }}" class="text-xs font-bold text-blue-600 hover:underline">Kelola →</a>
        </div>
        <h3 class="stat-label">Kategori Produk</h3>
        <div class="space-y-4">
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-black text-slate-800">{{ $categories->count() }}</p>
                    <p class="text-xs font-bold text-slate-400">Total Kategori</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-blue-600">Aktif</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Status</p>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-50 flex gap-2 overflow-x-auto pb-1">
                @foreach($categories as $cat)
                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold whitespace-nowrap">{{ $cat->name }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Stat Rental -->
    <div class="dashboard-card">
        <div class="flex justify-between items-start mb-6">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <a href="{{ route('admin.rentals') }}" class="text-xs font-bold text-purple-600 hover:underline">Kelola →</a>
        </div>
        <h3 class="stat-label">Aktivitas Sewa</h3>
        <div class="space-y-4">
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-black text-slate-800">{{ $stats['manage_rent']['rent_period']['cameras'] + $stats['manage_rent']['rent_period']['camps'] }}</p>
                    <p class="text-xs font-bold text-slate-400">Sewa Berjalan</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-purple-600">{{ $stats['manage_rent']['waiting_payment']['cameras'] + $stats['manage_rent']['waiting_payment']['camps'] }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Menunggu</p>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-50 grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-sm font-black text-slate-700">{{ $stats['manage_rent']['rent_finished']['cameras'] + $stats['manage_rent']['rent_finished']['camps'] }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Selesai</p>
                </div>
                <div class="flex items-center justify-center gap-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Update Langsung</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Rentals -->
    <div class="lg:col-span-2">
        <div class="dashboard-card h-full">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Penyewaan Terbaru
            </h3>
            <div class="space-y-4">
                @forelse($rentals->take(5) as $rental)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-teal-200 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-teal-600 shadow-sm">
                            {{ substr($rental->customer->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-800">{{ $rental->customer->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400">
                                {{ count($rental->details) }} Barang • {{ $rental->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $rental->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : ($rental->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600') }}">
                        @if($rental->status === 'pending') Menunggu @elseif($rental->status === 'active') Aktif @else Selesai @endif
                    </span>
                </div>
                @empty
                <p class="text-center text-slate-400 py-12 text-sm font-medium">Belum ada data penyewaan terbaru</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Info / Tips -->
    <div class="lg:col-span-1">
        <div class="dashboard-card h-full flex flex-col">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Informasi Penting
            </h3>
            <div class="space-y-4 flex-grow">
                <div class="p-5 bg-teal-50 border border-teal-100 rounded-3xl">
                    <h4 class="font-black text-teal-800 text-sm mb-2">Tips Pengelolaan</h4>
                    <p class="text-xs text-teal-700 leading-relaxed font-medium">Jangan lupa untuk segera memproses pengembalian barang agar stok unit kembali tersedia di katalog publik bagi penyewa lainnya.</p>
                </div>
                <div class="p-5 bg-blue-50 border border-blue-100 rounded-3xl">
                    <h4 class="font-black text-blue-800 text-sm mb-2">Pantau Pemasukan</h4>
                    <p class="text-xs text-blue-700 leading-relaxed font-medium">Gunakan halaman Laporan Pendapatan untuk melihat grafik keuntungan harian, mingguan, hingga tahunan Anda.</p>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-100">
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Sistem Berjalan Normal</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection