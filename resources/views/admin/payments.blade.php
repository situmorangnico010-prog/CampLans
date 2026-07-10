@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran - CampLens')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold">Verifikasi Pembayaran</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola dan verifikasi bukti pembayaran customer</p>
    </div>
    <a href="{{ route('admin.paymentSettings') }}" class="text-sm font-semibold text-teal-600 hover:underline">⚙️ Pengaturan Pembayaran</a>
</div>

{{-- Laporan Ringkas --}}
@php
    $report = [
        'waiting'  => \App\Models\Rental::where('transaction_status', 'waiting_verification')->count(),
        'approved' => \App\Models\Rental::where('transaction_status', 'payment_approved')->count(),
        'rejected' => \App\Models\Rental::where('transaction_status', 'payment_rejected')->count(),
        'paid'     => \App\Models\Rental::where('payment_status', 'paid')->sum('total_amount'),
    ];
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="stat-label">Menunggu Verifikasi</p>
        <p class="text-2xl font-black text-blue-600">{{ $report['waiting'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Diterima</p>
        <p class="text-2xl font-black text-teal-600">{{ $report['approved'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Ditolak</p>
        <p class="text-2xl font-black text-red-500">{{ $report['rejected'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Total Pendapatan</p>
        <p class="text-lg font-black text-slate-800">Rp{{ number_format($report['paid'], 0, ',', '.') }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="dashboard-card mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi / nama customer..."
               class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            <option value="">Semua Status</option>
            <option value="waiting_verification" @selected(request('status') === 'waiting_verification')>Menunggu Verifikasi</option>
            <option value="payment_approved" @selected(request('status') === 'payment_approved')>Diterima</option>
            <option value="payment_rejected" @selected(request('status') === 'payment_rejected')>Ditolak</option>
        </select>
        <button type="submit" class="bg-teal-600 text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition">Filter</button>
    </form>
</div>

<div class="dashboard-card overflow-hidden p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 font-semibold text-sm">Kode</th>
                    <th class="px-6 py-4 font-semibold text-sm">Customer</th>
                    <th class="px-6 py-4 font-semibold text-sm">Nominal</th>
                    <th class="px-6 py-4 font-semibold text-sm">Upload</th>
                    <th class="px-6 py-4 font-semibold text-sm">Status</th>
                    <th class="px-6 py-4 font-semibold text-sm text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($rentals as $rental)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-mono font-semibold text-sm">{{ $rental->order_code }}</p>
                        <p class="text-xs text-gray-400">#{{ $rental->booking_id }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-sm">{{ $rental->customer->name }}</p>
                        <p class="text-xs text-gray-500">{{ $rental->customer->email }}</p>
                    </td>
                    <td class="px-6 py-4 font-semibold text-teal-600 text-sm">
                        Rp{{ number_format($rental->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $rental->proof_uploaded_at?->format('d M Y, H:i') ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <x-rental-status-badge :rental="$rental" />
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.paymentDetail', $rental) }}"
                           class="inline-block bg-teal-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-teal-700 transition">
                            {{ $rental->transaction_status === 'waiting_verification' ? 'Verifikasi' : 'Detail' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada data pembayaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rentals->hasPages())
    <div class="px-6 py-4 border-t">{{ $rentals->links() }}</div>
    @endif
</div>
@endsection
