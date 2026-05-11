@extends('layouts.admin')
@section('title', 'Laporan Pendapatan - CampLens')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold">Laporan Pendapatan</h1>
    <p class="text-sm text-gray-500">Analisis statistik pemasukan uang dari penyewaan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-10">
    <!-- Quick Stats -->
    <div class="stat-card">
        <span class="stat-label">Hari Ini</span>
        <p class="text-xl font-bold text-teal-600">Rp{{ number_format($summaries['today'], 0, ',', '.') }}</p>
    </div>
    <div class="stat-card">
        <span class="stat-label">Minggu Ini</span>
        <p class="text-xl font-bold text-blue-600">Rp{{ number_format($summaries['this_week'], 0, ',', '.') }}</p>
    </div>
    <div class="stat-card">
        <span class="stat-label">Bulan Ini</span>
        <p class="text-xl font-bold text-indigo-600">Rp{{ number_format($summaries['this_month'], 0, ',', '.') }}</p>
    </div>
    <div class="stat-card">
        <span class="stat-label">Total Pendapatan</span>
        <p class="text-xl font-bold text-slate-800">Rp{{ number_format($totalIncome, 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart/Detailed Summary -->
    <div class="lg:col-span-2">
        <div class="dashboard-card h-full">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Riwayat Pemasukan Harian
            </h3>
            <div class="space-y-4">
                @forelse($summaries['history'] as $date => $amount)
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center font-bold text-xs">
                            {{ \Carbon\Carbon::parse($date)->format('d') }}
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</p>
                            <p class="text-[10px] text-gray-400">Pemasukan Berhasil</p>
                        </div>
                    </div>
                    <span class="font-bold text-teal-600">Rp{{ number_format($amount, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-center text-gray-400 py-12">Belum ada riwayat transaksi</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Yearly Summary -->
    <div class="lg:col-span-1">
        <div class="dashboard-card">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Statistik Tahunan
            </h3>
            <div class="p-6 bg-blue-50 rounded-3xl text-center">
                <p class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-2">Pemasukan Tahun {{ date('Y') }}</p>
                <p class="text-2xl font-black text-blue-700">Rp{{ number_format($summaries['this_year'], 0, ',', '.') }}</p>
            </div>
            <div class="mt-6 p-4 border border-dashed border-gray-200 rounded-2xl text-center">
                <p class="text-[10px] text-gray-400 leading-relaxed">
                    Data di atas adalah ringkasan pendapatan kotor dari seluruh transaksi penyewaan yang berstatus <strong>Lunas</strong>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
