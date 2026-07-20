@extends('layouts.app')
@section('title', 'Riwayat Rental - CampLens')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Rental</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status pesanan dan pembayaran Anda</p>
        </div>
        <a href="{{ route('items.index') }}" class="text-sm font-semibold text-teal-600 hover:underline">+ Booking Baru</a>
    </div>

    <div x-data="{ activeTab: 'payment' }" class="space-y-6">

        <div class="flex flex-wrap bg-gray-100 p-1.5 rounded-full w-fit shadow-inner gap-1">
            <button @click="activeTab = 'payment'"
                    :class="activeTab === 'payment' ? 'bg-teal-500 text-white shadow-md' : 'bg-transparent text-gray-500 hover:text-gray-700'"
                    class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                Pembayaran
                @php $paymentCount = $rentals->filter(fn($r) => in_array($r->transaction_status, ['waiting_payment', 'waiting_verification', 'payment_rejected']))->count(); @endphp
                @if($paymentCount > 0)<span class="ml-1 bg-white/30 px-1.5 rounded-full text-xs">{{ $paymentCount }}</span>@endif
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

        {{-- Tab: Pembayaran --}}
        <div x-show="activeTab === 'payment'" x-transition class="space-y-4">
            @forelse($rentals->filter(fn($r) => in_array($r->transaction_status, ['waiting_payment', 'waiting_verification', 'payment_rejected'])) as $rental)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row gap-4">
                    <img src="{{ $rental->details->first()?->item->image_url ?? 'https://placehold.co/100' }}"
                         class="w-20 h-20 rounded-xl object-cover bg-gray-100 shrink-0"
                         onerror="this.src='https://placehold.co/100/f5f5f7/86868b?text=IMG'">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $rental->order_code }}</h3>
                                <p class="text-sm text-gray-500">{{ $rental->rent_start_date->format('d M') }} → {{ $rental->rent_end_date->format('d M Y') }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <x-rental-status-badge :rental="$rental" />
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $rental->payment_status_badge_color }}">
                                    {{ $rental->payment_status_label }}
                                </span>
                            </div>
                        </div>
                        <p class="text-teal-600 font-bold text-lg mb-3">Rp{{ number_format($rental->total_price, 0, ',', '.') }}</p>
                        @if($rental->transaction_status === 'payment_rejected' && $rental->payment_note)
                        <p class="text-xs text-red-600 mb-2">❌ {{ $rental->payment_note }}</p>
                        @endif
                        @if($rental->expired_at && in_array($rental->transaction_status, ['waiting_payment', 'payment_rejected']))
                        <p class="text-xs {{ $rental->is_expired ? 'text-red-600' : 'text-yellow-600' }} mb-3">
                            ⏰ Batas bayar: {{ $rental->expired_at->format('d M Y, H:i') }}
                        </p>
                        @endif
                        <div class="flex flex-wrap gap-2">
                            @if(in_array($rental->transaction_status, ['waiting_payment', 'payment_rejected']))
                            <a href="{{ route('rentals.payment', $rental) }}" class="bg-teal-500 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-teal-600 transition">
                                {{ $rental->transaction_status === 'payment_rejected' ? 'Upload Ulang' : 'Bayar Sekarang' }}
                            </a>
                            @endif
                            <a href="{{ route('rentals.detail', $rental) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 text-gray-400">
                Tidak ada pembayaran tertunda
            </div>
            @endforelse
        </div>

        {{-- Tab: Berjalan --}}
        <div x-show="activeTab === 'ongoing'" x-transition class="space-y-4">
            @forelse($rentals->filter(fn($r) => in_array($r->transaction_status, ['payment_approved', 'processing', 'ready_for_pickup', 'on_rent', 'returned'])) as $rental)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row gap-4">
                    <img src="{{ $rental->details->first()?->item->image_url ?? 'https://placehold.co/100' }}"
                         class="w-24 h-24 rounded-xl object-cover bg-gray-100 shrink-0">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $rental->details->first()?->item->name ?? 'Barang Sewa' }}</h3>
                                <p class="text-sm text-gray-500">{{ $rental->order_code }}</p>
                            </div>
                            <x-rental-status-badge :rental="$rental" />
                        </div>
                        <p class="text-sm text-gray-500 mb-3">
                            {{ $rental->rent_start_date->format('d M') }} → {{ $rental->rent_end_date->format('d M Y') }}
                        </p>

                        @if($rental->transaction_status === 'on_rent')
                        <form action="{{ route('rentals.extend') }}" method="POST" class="flex flex-col sm:flex-row gap-2 mb-3">
                            @csrf
                            <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                            <input type="date" name="new_end" min="{{ $rental->rent_end_date->copy()->addDay()->format('Y-m-d') }}" required
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                            <button type="submit" class="bg-teal-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-teal-600 transition">Perpanjang</button>
                        </form>
                        @endif

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('rentals.detail', $rental) }}" class="text-sm font-semibold text-teal-600 hover:underline">Detail Transaksi</a>
                            @if(in_array($rental->transaction_status, ['payment_approved', 'on_rent', 'completed']))
                            <a href="{{ route('rentals.invoice', $rental) }}" class="text-sm font-semibold text-gray-600 hover:underline">Invoice</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 text-gray-400">
                Tidak ada pesanan berjalan
            </div>
            @endforelse
        </div>

        {{-- Tab: Riwayat --}}
        <div x-show="activeTab === 'history'" x-transition class="space-y-4">
            @forelse($rentals->filter(fn($r) => in_array($r->transaction_status, ['completed', 'cancelled', 'expired'])) as $rental)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex gap-4">
                <img src="{{ $rental->details->first()?->item->image_url ?? 'https://placehold.co/100' }}"
                     class="w-20 h-20 rounded-xl object-cover bg-gray-100 shrink-0">
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $rental->order_code }}</h3>
                            <p class="text-sm text-gray-500">{{ $rental->details->first()?->item->name ?? 'Barang Sewa' }}</p>
                        </div>
                        <x-rental-status-badge :rental="$rental" />
                    </div>
                    <p class="text-sm text-gray-500">{{ $rental->rent_start_date->format('d M') }} → {{ $rental->rent_end_date->format('d M Y') }}</p>
                    <p class="text-sm font-semibold text-teal-600 mt-1">Rp{{ number_format($rental->total_price, 0, ',', '.') }}</p>
                    @if($rental->late_fee > 0)
                    <p class="text-xs text-red-500">Denda: Rp{{ number_format($rental->late_fee, 0, ',', '.') }}</p>
                    @endif
                    <a href="{{ route('rentals.detail', $rental) }}" class="text-sm font-semibold text-teal-600 hover:underline mt-2 inline-block">Lihat Detail</a>
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
