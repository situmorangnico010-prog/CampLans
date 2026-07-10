@extends('layouts.app')
@section('title', 'Detail Pembayaran - CampLens')

@php use Illuminate\Support\Facades\Storage; @endphp

@push('styles')
<style>
    .countdown-ring { transition: stroke-dashoffset 1s linear; }
    .payment-card { background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-100 rounded-2xl mb-4">
            <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pembayaran</h1>
        <p class="text-gray-500 mt-1">{{ $rental->customer->name }} — selesaikan pembayaran sebelum waktu habis</p>
    </div>

    {{-- Expired Alert --}}
    @if($rental->is_expired)
    <div class="bg-orange-50 border border-orange-300 rounded-2xl p-4 mb-6 flex gap-3">
        <span class="text-2xl">⏰</span>
        <div>
            <p class="font-semibold text-orange-800">Batas Waktu Pembayaran Habis</p>
            <p class="text-sm text-orange-600">Booking ini telah kedaluwarsa. Silakan buat booking baru.</p>
        </div>
    </div>
    @endif

    {{-- Kode Transaksi & Countdown --}}
    <div class="payment-card text-white rounded-3xl p-6 mb-6 shadow-xl">
        <div class="flex justify-between items-start mb-5">
            <div>
                <p class="text-teal-200 text-xs font-semibold uppercase tracking-widest mb-1">Kode Transaksi</p>
                <p class="text-2xl font-bold tracking-wider">{{ $rental->kode_sewa }}</p>
            </div>
            <div class="text-right">
                <p class="text-teal-200 text-xs font-semibold uppercase tracking-widest mb-1">Status</p>
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full">
                    {{ $rental->status_label }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/20">
            <div>
                <p class="text-teal-200 text-xs mb-0.5">Tanggal Sewa</p>
                <p class="font-semibold text-sm">{{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-teal-200 text-xs mb-0.5">Tanggal Kembali</p>
                <p class="font-semibold text-sm">{{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-teal-200 text-xs mb-0.5">Metode Bayar</p>
                <p class="font-semibold text-sm">{{ $rental->payment_method_label }}</p>
            </div>
            <div>
                <p class="text-teal-200 text-xs mb-0.5">Total Pembayaran</p>
                <p class="font-bold text-xl">Rp{{ number_format($rental->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Countdown Timer --}}
    @if(!$rental->is_expired && $rental->transaction_status === 'waiting_payment')
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-6 text-center" x-data="countdown('{{ $rental->expired_at->toISOString() }}')">
        <p class="text-xs font-semibold text-yellow-700 uppercase tracking-wide mb-2">⏳ Batas Waktu Pembayaran</p>
        <div class="flex justify-center gap-3">
            <div class="bg-white rounded-xl px-4 py-2 shadow-sm min-w-[60px]">
                <p class="text-2xl font-bold text-yellow-700" x-text="hours">00</p>
                <p class="text-xs text-gray-500">Jam</p>
            </div>
            <div class="flex items-center text-yellow-600 font-bold text-xl">:</div>
            <div class="bg-white rounded-xl px-4 py-2 shadow-sm min-w-[60px]">
                <p class="text-2xl font-bold text-yellow-700" x-text="minutes">00</p>
                <p class="text-xs text-gray-500">Menit</p>
            </div>
            <div class="flex items-center text-yellow-600 font-bold text-xl">:</div>
            <div class="bg-white rounded-xl px-4 py-2 shadow-sm min-w-[60px]">
                <p class="text-2xl font-bold text-yellow-700" x-text="seconds">00</p>
                <p class="text-xs text-gray-500">Detik</p>
            </div>
        </div>
        <p class="text-xs text-yellow-600 mt-2">Bayar sebelum: {{ $rental->expired_at->format('d M Y, H:i') }} WIB</p>
    </div>
    @endif

    {{-- Detail Barang --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-4">Detail Barang Sewa</h3>
        @foreach($rental->details as $detail)
        <div class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-0">
            <img src="{{ $detail->item->image_url }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100"
                 onerror="this.src='https://placehold.co/48x48/f5f5f7/86868b?text=IMG'">
            <div class="flex-1">
                <p class="font-medium text-gray-800">{{ $detail->item->name }}</p>
                <p class="text-xs text-gray-500">{{ $detail->item->category->name ?? '-' }} • x{{ $detail->quantity }}</p>
            </div>
            <p class="font-semibold text-teal-600">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
        </div>
        @endforeach
        <div class="flex justify-between items-center pt-4 mt-2">
            <span class="font-bold text-gray-800">Total</span>
            <span class="font-bold text-xl text-teal-600">Rp{{ number_format($rental->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Info Rekening / QRIS / E-Wallet --}}
    @if(!$rental->is_expired)
    <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-4">Tujuan Pembayaran</h3>

        @if($rental->payment_method === 'transfer_bank')
        <div class="space-y-3">
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm text-gray-500">Bank</span>
                <span class="font-semibold text-gray-800">{{ $settings['bank_name'] ?? 'BCA' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm text-gray-500">Nomor Rekening</span>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-900 text-lg tracking-wider" id="accNumber">{{ $settings['account_number'] ?? '-' }}</span>
                    <button onclick="copyToClipboard('accNumber')" class="text-teal-500 hover:text-teal-700 transition" title="Salin">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm text-gray-500">Atas Nama</span>
                <span class="font-semibold text-gray-800">{{ $settings['account_name'] ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-sm text-gray-500">Nominal Transfer</span>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-teal-600 text-lg" id="nominal">Rp{{ number_format($rental->total_amount, 0, ',', '.') }}</span>
                    <button onclick="copyToClipboard('nominal')" class="text-teal-500 hover:text-teal-700 transition" title="Salin">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @elseif($rental->payment_method === 'qris')
        <div class="text-center">
            @if($qrisUrl = \App\Models\PaymentSetting::qrisImageUrl())
                <img src="{{ $qrisUrl }}" class="mx-auto max-w-[220px] rounded-xl border border-gray-200 shadow-sm" alt="QR Code Pembayaran">
                <p class="text-sm text-gray-500 mt-3">Scan QR Code menggunakan aplikasi dompet digital Anda</p>
            @else
                <div class="w-40 h-40 mx-auto bg-gray-100 rounded-xl flex items-center justify-center">
                    <p class="text-gray-400 text-xs text-center">QR Code belum<br>dikonfigurasi admin</p>
                </div>
            @endif
            <div class="mt-4 text-center">
                <span class="font-bold text-teal-600 text-xl">Rp{{ number_format($rental->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        @elseif($rental->payment_method === 'ewallet')
        <div class="space-y-3">
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm text-gray-500">Platform</span>
                <span class="font-semibold text-gray-800">{{ $settings['ewallet_name'] ?? 'GoPay' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm text-gray-500">Nomor</span>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-900 text-lg" id="ewalletNum">{{ $settings['ewallet_number'] ?? '-' }}</span>
                    <button onclick="copyToClipboard('ewalletNum')" class="text-teal-500 hover:text-teal-700 transition" title="Salin">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-sm text-gray-500">Nominal</span>
                <span class="font-bold text-teal-600 text-lg">Rp{{ number_format($rental->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        @endif
    </div>

    {{-- Upload Bukti Pembayaran --}}
    @if(in_array($rental->transaction_status, ['waiting_payment', 'payment_rejected']))
    <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm" x-data="{
        preview: null,
        dragging: false,
        handleFile(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => { this.preview = e.target.result; };
            reader.readAsDataURL(file);
        }
    }">
        <h3 class="font-bold text-gray-800 mb-1">Upload Bukti Pembayaran</h3>
        @if($rental->transaction_status === 'payment_rejected')
        <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4 text-sm text-red-700">
            <strong>Pembayaran ditolak.</strong>
            @if($rental->payment_note) Alasan: {{ $rental->payment_note }} @endif
            <br>Silakan upload ulang bukti pembayaran yang benar.
        </div>
        @endif
        <p class="text-sm text-gray-500 mb-4">Format: JPG, PNG, WEBP. Maksimal 5 MB.</p>

        <form action="{{ route('rentals.uploadProof', $rental) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="payment_method" value="{{ $rental->payment_method }}">

            {{-- Dropzone --}}
            <div class="border-2 border-dashed rounded-xl p-6 text-center transition-colors mb-4 cursor-pointer"
                 :class="dragging ? 'border-teal-400 bg-teal-50' : 'border-gray-300 hover:border-teal-400'"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])"
                 @click="$refs.fileInput.click()">
                <div x-show="!preview">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau seret file ke sini</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP hingga 5 MB</p>
                </div>
                <div x-show="preview" class="relative">
                    <img :src="preview" class="max-h-48 mx-auto rounded-lg object-contain">
                    <button type="button" @click.stop="preview = null; $refs.fileInput.value = ''"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">✕</button>
                </div>
                <input type="file" name="payment_proof" accept="image/*" class="hidden" x-ref="fileInput"
                       @change="handleFile($event.target.files[0])">
            </div>

            @error('payment_proof')
                <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full bg-teal-600 text-white py-3.5 rounded-xl font-semibold hover:bg-teal-700 transition shadow-sm">
                📤 Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
    @elseif($rental->transaction_status === 'waiting_verification')
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex gap-3">
        <span class="text-2xl">🔍</span>
        <div>
            <p class="font-semibold text-blue-800">Bukti Pembayaran Sedang Diverifikasi</p>
            <p class="text-sm text-blue-600">Admin sedang memeriksa pembayaran Anda. Mohon tunggu konfirmasi.</p>
            @if($rental->payment_proof)
            <a href="{{ $rental->payment_proof_url }}" target="_blank" class="text-sm text-blue-700 font-medium underline mt-1 inline-block">
                Lihat bukti yang diunggah →
            </a>
            @endif
        </div>
    </div>
    @endif
    @endif

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('rentals.index') }}" class="flex-1 text-center bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 transition">
            ← Riwayat Rental
        </a>
        <a href="{{ route('rentals.detail', $rental) }}" class="flex-1 text-center bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-50 transition">
            Detail Transaksi
        </a>
        @if(in_array($rental->transaction_status, ['payment_approved', 'on_rent', 'completed']))
        <a href="{{ route('rentals.invoice', $rental) }}" class="flex-1 text-center bg-teal-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-teal-700 transition">
            🧾 Cetak Invoice
        </a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const text = el.innerText.replace(/[^0-9]/g, '') || el.innerText;
    navigator.clipboard.writeText(el.innerText).then(() => {
        const btn = el.nextElementSibling;
        if (btn) { btn.innerHTML = '✅'; setTimeout(() => btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>', 2000); }
    });
}

function countdown(expiryISO) {
    return {
        hours: '00', minutes: '00', seconds: '00',
        init() {
            this.tick();
            setInterval(() => this.tick(), 1000);
        },
        tick() {
            const diff = new Date(expiryISO) - new Date();
            if (diff <= 0) { this.hours = '00'; this.minutes = '00'; this.seconds = '00'; return; }
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            this.hours   = String(h).padStart(2, '0');
            this.minutes = String(m).padStart(2, '0');
            this.seconds = String(s).padStart(2, '0');
        }
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.data('countdown', countdown);
});

</script>
@endpush
