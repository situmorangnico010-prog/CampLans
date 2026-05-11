@extends('layouts.admin')
@section('title', 'Kelola Penyewaan - CampLens')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold">Kelola Penyewaan</h1>
</div>

<div class="dashboard-card overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-4 font-semibold">ID</th>
                <th class="px-6 py-4 font-semibold">Pelanggan</th>
                <th class="px-6 py-4 font-semibold">Tanggal</th>
                <th class="px-6 py-4 font-semibold">Total</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($rentals as $rental)
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'active' => 'bg-green-100 text-green-800',
                    'completed' => 'bg-blue-100 text-blue-800'
                ];
            @endphp
            <tr>
                <td class="px-6 py-4 font-mono text-sm">#{{ $rental->id }}</td>
                <td class="px-6 py-4">
                    <div class="font-medium">{{ $rental->customer->name }}</div>
                    <div class="text-xs text-gray-500">{{ $rental->customer->email }}</div>
                </td>
                <td class="px-6 py-4 text-sm">
                    {{ \Carbon\Carbon::parse($rental->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}
                </td>
                <td class="px-6 py-4 font-semibold text-teal-600">Rp{{ number_format($rental->total_amount, 0, ',', '.') }}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$rental->status] ?? 'bg-gray-100' }}">
                        @if($rental->status === 'pending') MENUNGGU @elseif($rental->status === 'active') AKTIF @else SELESAI @endif
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    @if($rental->status === 'pending')
                        <form method="POST" action="{{ route('admin.approve') }}" class="inline">
                            @csrf
                            <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                            <button type="submit" class="bg-green-500 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-green-600 transition">Setujui</button>
                        </form>
                    @elseif($rental->status === 'active')
                        <form method="POST" action="{{ route('admin.return') }}" class="inline">
                            @csrf
                            <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                            <input type="hidden" name="return_date" value="{{ now() }}">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-600 transition">Kembalikan</button>
                        </form>
                    @else
                        <span class="text-gray-400 text-xs font-bold">SELESAI</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
