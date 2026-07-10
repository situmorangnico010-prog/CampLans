@extends('layouts.admin')
@section('title', 'Pengaturan Pembayaran - CampLens')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold">Pengaturan Pembayaran Manual</h1>
    <p class="text-gray-500 text-sm mt-1">Konfigurasi rekening tujuan, QRIS, dan batas waktu pembayaran</p>
</div>

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.savePaymentSettings') }}" enctype="multipart/form-data" class="dashboard-card space-y-6">
        @csrf

        <div>
            <h3 class="font-bold text-gray-800 mb-4">Transfer Bank</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bank Tujuan</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $settings['bank_name'] ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Rekening</label>
                    <input type="text" name="account_number" value="{{ old('account_number', $settings['account_number'] ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none font-mono">
                    @error('account_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" value="{{ old('account_name', $settings['account_name'] ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    @error('account_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="border-t pt-6">
            <h3 class="font-bold text-gray-800 mb-4">QRIS Manual</h3>
            @if($qrisUrl = \App\Models\PaymentSetting::qrisImageUrl())
            <div class="mb-4">
                <p class="text-xs text-gray-500 mb-2">QR Code saat ini:</p>
                <img src="{{ $qrisUrl }}" alt="QRIS" class="max-w-[180px] rounded-xl border border-gray-200">
            </div>
            @else
            <p class="text-xs text-amber-600 mb-4">Belum ada QR Code. Upload gambar lalu klik <strong>Simpan Pengaturan</strong>.</p>
            @endif
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Upload QR Code (opsional)</label>
                <input type="file" name="qris_image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-teal-50 file:text-teal-700 file:font-semibold">
                @error('qris_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="border-t pt-6">
            <h3 class="font-bold text-gray-800 mb-4">E-Wallet</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Platform</label>
                    <input type="text" name="ewallet_name" value="{{ old('ewallet_name', $settings['ewallet_name'] ?? '') }}"
                           placeholder="GoPay, OVO, Dana..."
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor</label>
                    <input type="text" name="ewallet_number" value="{{ old('ewallet_number', $settings['ewallet_number'] ?? '') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                </div>
            </div>
        </div>

        <div class="border-t pt-6">
            <h3 class="font-bold text-gray-800 mb-4">Batas Waktu Pembayaran</h3>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jam (setelah checkout)</label>
                <input type="number" name="payment_hours" value="{{ old('payment_hours', $settings['payment_hours'] ?? '24') }}" required min="1" max="72"
                       class="w-32 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Booking otomatis expired jika bukti tidak diunggah dalam waktu ini.</p>
                @error('payment_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="border-t pt-6">
            <h3 class="font-bold text-gray-800 mb-4">Denda Keterlambatan</h3>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Denda Per Hari (Rp)</label>
                <input type="number" name="penalty_per_day" value="{{ old('penalty_per_day', $settings['penalty_per_day'] ?? '50000') }}" required min="0"
                       class="w-48 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Denda yang dikenakan per hari keterlambatan pengembalian barang.</p>
                @error('penalty_per_day') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit" class="w-full bg-teal-600 text-white py-3.5 rounded-xl font-bold hover:bg-teal-700 transition">
            Simpan Pengaturan
        </button>
    </form>
</div>
@endsection
