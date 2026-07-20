{{-- 
Komponen Badge Status Rental (Rental Status Badge Component)
Menampilkan badge berwarna secara dinamis berdasarkan status transaksi/penyewaan saat ini.
--}}
@props(['rental'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold ' . $rental->status_badge_color]) }}>
    {{ $rental->status_label }}
</span>
