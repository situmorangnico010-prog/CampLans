{{-- 
Komponen Timeline Status Rental (Rental Timeline Component)
Menampilkan tahapan proses penyewaan (Alur transaksi mulai dari menunggu pembayaran, verifikasi, masa sewa, hingga selesai/kembali) dalam bentuk timeline visual yang interaktif.
--}}
@props(['rental'])

@php
    $steps = $rental->timeline_steps;
    $currentIndex = collect($steps)->search(fn ($s) => $s['active']);
@endphp

<div class="space-y-0">
    @foreach($steps as $index => $step)
        @php
            $isDone = $index < $currentIndex || ($step['active'] && in_array($rental->transaction_status, ['completed', 'cancelled', 'expired']));
            $isActive = $step['active'] && !in_array($rental->transaction_status, ['completed', 'cancelled', 'expired']);
            $isLast = $index === count($steps) - 1;
        @endphp
        <div class="flex gap-3">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                    {{ $isDone ? 'bg-teal-600 text-white' : ($isActive ? 'bg-teal-100 text-teal-700 ring-2 ring-teal-500' : 'bg-gray-100 text-gray-400') }}">
                    @if($isDone && !$isActive)
                        ✓
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                @if(!$isLast)
                    <div class="w-0.5 flex-1 min-h-[24px] {{ $isDone ? 'bg-teal-400' : 'bg-gray-200' }}"></div>
                @endif
            </div>
            <div class="pb-5 {{ $isLast ? 'pb-0' : '' }}">
                <p class="text-sm font-semibold {{ $isActive ? 'text-teal-700' : ($isDone ? 'text-gray-800' : 'text-gray-400') }}">
                    {{ $step['label'] }}
                </p>
                @if(!empty($step['description']))
                    <p class="text-xs text-gray-500 mt-0.5">{{ $step['description'] }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
