{{-- 
Halaman Detail Transaksi (Rentals Detail)
Menampilkan rincian lengkap mengenai transaksi sewa tertentu, termasuk detail alat,
jadwal sewa, timeline status, status pembayaran, info verifikasi admin, dan tombol aksi (cetak invoice, perpanjang).
--}}
@extends('layouts.app')
@section('title', 'Detail Transaksi - CampLens')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-sm text-gray-500">Kode Transaksi</p>
            <h1 class="text-2xl font-bold text-gray-900">{{ $rental->order_code }}</h1>
            <p class="text-sm text-gray-500 mt-1">Booking #{{ $rental->booking_id }} • {{ $rental->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <x-rental-status-badge :rental="$rental" />
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $rental->payment_status_badge_color }}">
                {{ $rental->payment_status_label }}
            </span>
        </div>
    </div>

    {{-- Alerts --}}
    @if($rental->transaction_status === 'payment_rejected')
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex gap-3">
        <span class="text-2xl">❌</span>
        <div>
            <p class="font-semibold text-red-800">Pembayaran Ditolak</p>
            <p class="text-sm text-red-600">{{ $rental->payment_note ?: 'Bukti pembayaran tidak valid. Silakan upload ulang.' }}</p>
            <a href="{{ route('rentals.payment', $rental) }}" class="inline-block mt-2 text-sm font-semibold text-red-700 underline">Upload ulang bukti →</a>
        </div>
    </div>
    @elseif($rental->transaction_status === 'waiting_verification')
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex gap-3">
        <span class="text-2xl">🔍</span>
        <div>
            <p class="font-semibold text-blue-800">Menunggu Verifikasi Admin</p>
            <p class="text-sm text-blue-600">Bukti pembayaran sedang diperiksa. Mohon tunggu konfirmasi.</p>
        </div>
    </div>
    @elseif($rental->transaction_status === 'payment_approved')
    <div class="bg-teal-50 border border-teal-200 rounded-2xl p-4 mb-6 flex gap-3">
        <span class="text-2xl">✅</span>
        <div>
            <p class="font-semibold text-teal-800">Pembayaran Berhasil Diverifikasi</p>
            <p class="text-sm text-teal-600">Pembayaran Anda telah dikonfirmasi. Barang akan segera disiapkan.</p>
        </div>
    </div>
    @elseif($rental->transaction_status === 'expired')
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 mb-6 flex gap-3">
        <span class="text-2xl">⏰</span>
        <div>
            <p class="font-semibold text-orange-800">Pembayaran Kedaluwarsa</p>
            <p class="text-sm text-orange-600">Batas waktu pembayaran habis. Booking dibatalkan otomatis.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Timeline --}}
        <div class="lg:col-span-1 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <h3 class="font-bold text-gray-800 mb-4">Status Rental</h3>
            <x-rental-timeline :rental="$rental" />
        </div>

        {{-- Info Transaksi --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Informasi Transaksi</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Customer</p>
                        <p class="font-semibold">{{ $rental->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Metode Pembayaran</p>
                        <p class="font-semibold">{{ $rental->payment_method_label }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Mulai Sewa</p>
                        <p class="font-semibold">{{ $rental->rent_start_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Kembali</p>
                        <p class="font-semibold">{{ $rental->rent_end_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Durasi</p>
                        <p class="font-semibold">{{ $rental->rental_duration_days }} hari</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Total Pembayaran</p>
                        <p class="font-bold text-teal-600 text-lg">Rp{{ number_format($rental->total_price, 0, ',', '.') }}</p>
                    </div>
                    @if($rental->late_fee > 0)
                    <div>
                        <p class="text-gray-500">Denda Keterlambatan</p>
                        <p class="font-semibold text-red-600">Rp{{ number_format($rental->late_fee, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    @if($rental->expired_at && in_array($rental->transaction_status, ['waiting_payment', 'payment_rejected']))
                    <div class="col-span-2">
                        <p class="text-gray-500">Batas Waktu Pembayaran</p>
                        <p class="font-semibold {{ $rental->is_expired ? 'text-red-600' : 'text-yellow-700' }}">
                            {{ $rental->expired_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Barang Disewa --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Barang yang Disewa</h3>
                @foreach($rental->details as $detail)
                <div class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-0">
                    <img src="{{ $detail->item->image_url }}" class="w-14 h-14 rounded-lg object-cover bg-gray-100"
                         onerror="this.src='https://placehold.co/56x56/f5f5f7/86868b?text=IMG'">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $detail->item->name }}</p>
                        <p class="text-xs text-gray-500">{{ $detail->item->category->name ?? '-' }} • x{{ $detail->quantity }}</p>
                    </div>
                    <p class="font-semibold text-teal-600">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>

            {{-- Bukti Pembayaran --}}
            @if($rental->payment_proof)
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">📎 Bukti Pembayaran</h3>
                <div class="flex flex-col sm:flex-row gap-4 items-start">
                    <a href="{{ $rental->payment_proof_url }}" target="_blank" class="block">
                        <img src="{{ $rental->payment_proof_url }}" alt="Bukti Pembayaran"
                             class="max-w-xs rounded-xl border border-gray-200 shadow-sm hover:opacity-90 transition">
                    </a>
                    <div class="text-sm text-gray-600 space-y-1">
                        @if($rental->proof_uploaded_at)
                        <p>Diunggah: {{ $rental->proof_uploaded_at->format('d M Y, H:i') }}</p>
                        @endif
                        @if($rental->verified_at)
                        <p>Diverifikasi: {{ $rental->verified_at->format('d M Y, H:i') }}</p>
                        @if($rental->verifiedBy)
                        <p>Oleh: {{ $rental->verifiedBy->name }}</p>
                        @endif
                        @endif
                        @if($rental->payment_note)
                        <p class="text-gray-800 mt-2"><strong>Catatan:</strong> {{ $rental->payment_note }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('rentals.index') }}" class="flex-1 text-center bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 transition">
            ← Riwayat Rental
        </a>
        @if(in_array($rental->transaction_status, ['waiting_payment', 'payment_rejected']))
        <a href="{{ route('rentals.payment', $rental) }}" class="flex-1 text-center bg-teal-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-teal-700 transition">
            💳 Bayar / Upload Bukti
        </a>
        @endif
        @if(in_array($rental->transaction_status, ['payment_approved', 'processing', 'ready_for_pickup', 'on_rent', 'returned', 'completed']))
        <a href="{{ route('rentals.invoice', $rental) }}" class="flex-1 text-center bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-50 transition">
            🧾 Invoice
        </a>
        @endif
    </div>
</div>
@endsection
