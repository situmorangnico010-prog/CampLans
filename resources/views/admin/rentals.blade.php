@extends('layouts.admin')
@section('title', 'Kelola Penyewaan - CampLens')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold">Kelola Penyewaan</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar transaksi rental & kelola status fulfillment</p>
    </div>
    <a href="{{ route('admin.payments') }}" class="text-sm font-semibold text-teal-600 hover:underline">Verifikasi Pembayaran →</a>
</div>

{{-- Filter & Search --}}
<div class="dashboard-card mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama / email..."
               class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            <option value="">Semua Status</option>
            @foreach($statusOptions as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
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
                    <th class="px-6 py-4 font-semibold text-sm">Pelanggan</th>
                    <th class="px-6 py-4 font-semibold text-sm">Periode</th>
                    <th class="px-6 py-4 font-semibold text-sm">Total</th>
                    <th class="px-6 py-4 font-semibold text-sm">Status</th>
                    <th class="px-6 py-4 font-semibold text-sm text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($rentals as $rental)
                @php
                    $nextStatus = match($rental->transaction_status) {
                        'payment_approved' => ['processing', 'Proses'],
                        'processing'       => ['ready_for_pickup', 'Siap Diambil'],
                        'ready_for_pickup' => ['on_rent', 'Mulai Sewa'],
                        'on_rent'          => ['returned', 'Dikembalikan'],
                        'returned'         => ['completed', 'Selesai'],
                        default            => null,
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-mono font-semibold text-sm">{{ $rental->order_code }}</p>
                        <p class="text-xs text-gray-400">#{{ $rental->booking_id }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-sm">{{ $rental->customer->name }}</p>
                        <p class="text-xs text-gray-500">{{ $rental->customer->email }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        {{ $rental->rent_start_date->format('d M') }} — {{ $rental->rent_end_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-teal-600 text-sm">
                        Rp{{ number_format($rental->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <x-rental-status-badge :rental="$rental" />
                        <p class="text-[10px] text-gray-400 mt-1">{{ $rental->payment_status_label }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex flex-wrap justify-end gap-2">
                            @if($rental->transaction_status === 'waiting_verification')
                            <a href="{{ route('admin.paymentDetail', $rental) }}"
                               class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-600 transition">
                                Verifikasi
                            </a>
                            @endif

                            @if($nextStatus)
                            <form method="POST" action="{{ route('admin.updateStatus') }}" class="inline">
                                @csrf
                                <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                                <input type="hidden" name="new_status" value="{{ $nextStatus[0] }}">
                                <button type="submit" class="bg-teal-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-teal-700 transition">
                                    {{ $nextStatus[1] }}
                                </button>
                            </form>
                            @endif

                            @if(!in_array($rental->transaction_status, ['completed', 'cancelled', 'expired', 'returned']))
                            <form method="POST" action="{{ route('admin.cancelRental') }}" class="inline"
                                  onsubmit="return confirm('Batalkan rental ini? Stok akan dikembalikan.')">
                                @csrf
                                <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                                <button type="submit" class="bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-300 transition">
                                    Batal
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada data rental</td>
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
