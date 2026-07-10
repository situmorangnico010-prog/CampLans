@extends('layouts.admin')
@section('title', 'Detail Verifikasi - ' . $rental->order_code)

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.payments') }}" class="text-sm text-teal-600 font-semibold hover:underline">← Kembali ke Daftar Pembayaran</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Data Customer & Rental --}}
    <div class="space-y-6">
        <div class="dashboard-card">
            <h2 class="text-lg font-bold mb-4">Data Customer</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Nama</span><span class="font-semibold">{{ $rental->customer->name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Email</span><span class="font-semibold">{{ $rental->customer->email }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Kode User</span><span class="font-mono">{{ $rental->customer->kode_user ?? '-' }}</span></div>
            </div>
        </div>

        <div class="dashboard-card">
            <h2 class="text-lg font-bold mb-4">Data Rental</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Kode Transaksi</span><span class="font-mono font-bold">{{ $rental->order_code }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Tanggal Booking</span><span>{{ $rental->created_at->format('d M Y, H:i') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Periode Sewa</span><span>{{ $rental->rent_start_date->format('d M') }} — {{ $rental->rent_end_date->format('d M Y') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Durasi</span><span>{{ $rental->rental_duration_days }} hari</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Metode</span><span>{{ $rental->payment_method_label }}</span></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Status</span>
                    <x-rental-status-badge :rental="$rental" />
                </div>
            </div>

            <div class="mt-4 pt-4 border-t">
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Barang Disewa</p>
                @foreach($rental->details as $detail)
                <div class="flex justify-between text-sm py-1">
                    <span>{{ $detail->item->name }} x{{ $detail->quantity }}</span>
                    <span class="font-semibold">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
                <div class="flex justify-between font-bold text-teal-600 mt-2 pt-2 border-t">
                    <span>Total</span>
                    <span>Rp{{ number_format($rental->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bukti & Verifikasi --}}
    <div class="space-y-6">
        <div class="dashboard-card">
            <h2 class="text-lg font-bold mb-4">Bukti Pembayaran</h2>

            @if($rental->payment_proof)
            <a href="{{ $rental->payment_proof_url }}" target="_blank" class="block mb-4">
                <img src="{{ $rental->payment_proof_url }}" alt="Bukti Pembayaran"
                     class="w-full max-h-96 object-contain rounded-xl border border-gray-200 bg-gray-50">
            </a>
            <div class="text-sm text-gray-600 space-y-1">
                <p><strong>Nominal:</strong> Rp{{ number_format($rental->total_price, 0, ',', '.') }}</p>
                <p><strong>Tanggal Upload:</strong> {{ $rental->proof_uploaded_at?->format('d M Y, H:i') ?? '-' }}</p>
                @if($rental->verified_at)
                <p><strong>Verifikasi:</strong> {{ $rental->verified_at->format('d M Y, H:i') }} oleh {{ $rental->verifiedBy?->name }}</p>
                @endif
            </div>
            @else
            <p class="text-gray-400 text-center py-8">Belum ada bukti pembayaran diunggah</p>
            @endif
        </div>

        @if($rental->transaction_status === 'waiting_verification')
        <div class="dashboard-card">
            <h2 class="text-lg font-bold mb-4">Verifikasi Pembayaran</h2>
            <form method="POST" action="{{ route('admin.verifyPayment') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="rental_id" value="{{ $rental->id }}">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Verifikasi (opsional)</label>
                    <textarea name="note" rows="3" placeholder="Catatan untuk customer jika ditolak..."
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none resize-none">{{ old('note') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" name="action" value="approve"
                            class="flex-1 bg-teal-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-teal-700 transition">
                        ✅ Terima Pembayaran
                    </button>
                    <button type="submit" name="action" value="reject"
                            onclick="return confirm('Tolak pembayaran ini? Customer dapat upload ulang.')"
                            class="flex-1 bg-red-500 text-white py-3 rounded-xl font-bold text-sm hover:bg-red-600 transition">
                        ❌ Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
        @elseif($rental->payment_note)
        <div class="dashboard-card">
            <h2 class="text-lg font-bold mb-2">Catatan Verifikasi</h2>
            <p class="text-sm text-gray-600">{{ $rental->payment_note }}</p>
        </div>
        @endif

        @if($rental->transaction_status === 'payment_approved')
        <div class="dashboard-card bg-teal-50 border-teal-200">
            <p class="text-teal-800 font-semibold">✅ Pembayaran telah diterima.</p>
            <a href="{{ route('admin.rentals', ['search' => $rental->order_code]) }}" class="inline-block mt-3 text-sm text-teal-700 font-semibold underline">
                Kelola status rental →
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
