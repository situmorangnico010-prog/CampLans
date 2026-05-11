@extends('layouts.app')
@section('title', $item->name . ' - CampLans')

@push('styles')
<style>
    .main-img { aspect-ratio: 4/3; object-fit: cover; border-radius: 1.5rem; cursor: zoom-in; transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
    .main-img:hover { transform: scale(1.02); }
    .thumb { width: 80px; height: 60px; object-fit: cover; border-radius: 0.75rem; cursor: pointer; border: 2px solid transparent; opacity: 0.6; transition: all 0.3s; }
    .thumb.active, .thumb:hover { border-color: #14b8a6; opacity: 1; transform: translateY(-3px); box-shadow: 0 4px 12px rgba(20, 184, 166, 0.2); }
    
    .tab-btn { padding: 1rem; font-weight: 600; border-bottom: 2px solid transparent; color: #64748b; transition: all 0.3s; white-space: nowrap; }
    .dark .tab-btn { color: #94a3b8; }
    .tab-btn.active { color: #14b8a6; border-bottom-color: #14b8a6; }
    .tab-content { display: none; animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .booking-card { 
        background: rgba(255, 255, 255, 0.7); 
        backdrop-filter: blur(24px) saturate(180%); 
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.4); 
    }
    .dark .booking-card { background: rgba(30, 41, 59, 0.7); border-color: rgba(255,255,255,0.08); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-12">
    <!-- Breadcrumbs -->
    <nav class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 py-3 px-4 sticky top-16 z-30">
        <div class="max-w-7xl mx-auto flex items-center text-sm text-gray-500 dark:text-gray-400 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="hover:text-teal-600 transition">Beranda</a>
            <span class="mx-2">/</span>
            <a href="{{ route('items.index') }}" class="hover:text-teal-600 transition">Katalog</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white font-medium">{{ $item->name }}</span>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Gallery & Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Gallery -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
                <img id="mainImage" src="{{ $item->image_url }}" class="main-img w-full mb-4" onerror="this.src='https://placehold.co/800x600/e5e7eb/6b7280?text=Product+Image'">
                <div class="flex gap-3 overflow-x-auto pb-2">
                    <img src="{{ $item->image_url }}" class="thumb active" onclick="changeImage(this)" onerror="this.src='https://placehold.co/80x60/e5e7eb/6b7280?text=IMG'">
                    <!-- Mock thumbnails -->
                    <img src="https://placehold.co/80x60/e5e7eb/6b7280?text=Side" class="thumb" onclick="changeImage(this)">
                    <img src="https://placehold.co/80x60/e5e7eb/6b7280?text=Back" class="thumb" onclick="changeImage(this)">
                    <img src="https://placehold.co/80x60/e5e7eb/6b7280?text=Box" class="thumb" onclick="changeImage(this)">
                </div>
            </div>

            <!-- Header Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                    <div>
                        <span class="inline-block px-3 py-1 bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 text-xs font-bold rounded-full mb-2 uppercase tracking-wide">{{ $item->category->name }}</span>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">{{ $item->name }}</h1>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-3xl font-bold text-teal-600 dark:text-teal-400">Rp {{ number_format($item->daily_rate, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">/ hari</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4">
                    <span class="flex items-center gap-1"><span class="text-yellow-400">★★★★☆</span> (4.8)</span>
                    <span class="hidden sm:inline">•</span>
                    <span class="flex items-center gap-1.5 {{ $item->stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                        <span class="w-2 h-2 rounded-full {{ $item->stock > 0 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        {{ $item->stock > 0 ? 'Tersedia (' . $item->stock . ' unit)' : 'Habis Tersewa' }}
                    </span>
                </div>
            </div>

            <!-- Tabs: Deskripsi, Spesifikasi, Review -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" x-data="{ tab: 'desc' }">
                <div class="flex border-b border-gray-100 dark:border-gray-700 overflow-x-auto hide-scrollbar">
                    <button @click="tab = 'desc'" :class="{ 'active': tab === 'desc' }" class="tab-btn flex-1 text-center">Deskripsi</button>
                    <button @click="tab = 'specs'" :class="{ 'active': tab === 'specs' }" class="tab-btn flex-1 text-center">Spesifikasi</button>
                    <button @click="tab = 'reviews'" :class="{ 'active': tab === 'reviews' }" class="tab-btn flex-1 text-center">Ulasan</button>
                </div>
                <div class="p-6 min-h-[200px]">
                    <div x-show="tab === 'desc'" class="tab-content active text-gray-600 dark:text-gray-300 leading-relaxed space-y-3">
                        <p>Sewa {{ $item->name }} berkualitas tinggi untuk kebutuhan petualangan atau profesional Anda. Barang dalam kondisi prima, terawat rutin, dan dilengkapi dengan aksesoris standar.</p>
                        <ul class="space-y-2 list-disc pl-5 mt-2">
                            <li>Kondisi 95% baru & terkalibrasi</li>
                            <li>Termasuk tas original & baterai cadangan</li>
                            <li>Garansi kerusakan teknis selama masa sewa</li>
                            <li>Pengambilan & pengembalian fleksibel</li>
                        </ul>
                    </div>
                    <div x-show="tab === 'specs'" class="tab-content">
                        <table class="w-full text-sm">
                            <tr class="border-b border-gray-100 dark:border-gray-700"><td class="py-3 text-gray-500 w-1/3">Kategori</td><td class="py-3 font-medium text-gray-900 dark:text-white">{{ $item->category->name }}</td></tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700"><td class="py-3 text-gray-500">Stok Tersedia</td><td class="py-3 font-medium text-gray-900 dark:text-white">{{ $item->stock }} unit</td></tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700"><td class="py-3 text-gray-500">Harga Sewa</td><td class="py-3 font-medium text-gray-900 dark:text-white">Rp {{ number_format($item->daily_rate, 0, ',', '.') }} / hari</td></tr>
                            <tr><td class="py-3 text-gray-500">Kode Barang</td><td class="py-3 font-medium text-gray-900 dark:text-white">#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
                        </table>
                    </div>
                    <div x-show="tab === 'reviews'" class="tab-content space-y-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="flex justify-between mb-1"><span class="font-semibold text-gray-900 dark:text-white">Budi S.</span><span class="text-yellow-400 text-xs">★★★★★</span></div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Barang sesuai deskripsi, sangat membantu trip saya minggu lalu! Admin juga fast response.</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="flex justify-between mb-1"><span class="font-semibold text-gray-900 dark:text-white">Anita R.</span><span class="text-yellow-400 text-xs">★★★★☆</span></div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Pelayanan cepat, kondisi barang bersih. Recommended buat yang mau coba gear baru.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sticky Booking Card -->
        <div class="lg:col-span-1">
            <div class="booking-card sticky top-24 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Booking Sekarang</h3>
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4" onsubmit="return validateDates()">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start" id="startDate" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition text-gray-900 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Kembali</label>
                        <input type="date" name="end" id="endDate" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition text-gray-900 dark:text-white">
                    </div>

                    <!-- Dynamic Price Summary -->
                    <div id="priceSummary" class="hidden p-4 bg-teal-50 dark:bg-teal-900/20 rounded-xl border border-teal-100 dark:border-teal-800 transition-all">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1"><span>Durasi:</span><span id="durationText" class="font-medium">0 hari</span></div>
                        <div class="flex justify-between text-lg font-bold text-teal-700 dark:text-teal-400 pt-2 border-t border-teal-200 dark:border-teal-800 mt-2"><span>Total:</span><span id="totalText">Rp 0</span></div>
                    </div>

                    <button type="submit" class="w-full bg-teal-500 text-white py-3.5 rounded-xl font-bold text-lg hover:bg-teal-600 transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Tambah ke Keranjang
                    </button>
                    <p class="text-xs text-center text-gray-400 mt-2">🔒 Gratis pembatalan 24 jam sebelum mulai</p>
                </form>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <section class="max-w-7xl mx-auto px-4 py-8 border-t border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Produk Serupa</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($relatedItems as $rel)
            <a href="{{ route('items.show', $rel) }}" class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition group block">
                <div class="aspect-square bg-gray-100 dark:bg-gray-700 overflow-hidden">
                    <img src="{{ $rel->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.src='https://placehold.co/200x200/e5e7eb/6b7280?text=IMG'">
                </div>
                <div class="p-3">
                    <p class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ $rel->name }}</p>
                    <p class="text-teal-600 dark:text-teal-400 font-bold text-sm mt-1">Rp{{ number_format($rel->daily_rate, 0, ',', '.') }}</p>
                </div>
            </a>
            @empty
            <p class="text-gray-500 col-span-4">Tidak ada produk serupa.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    // Gallery Switcher
    function changeImage(thumb) {
        document.getElementById('mainImage').src = thumb.src;
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    }

    // Date & Price Calculator
    const startInput = document.getElementById('startDate');
    const endInput = document.getElementById('endDate');
    const summary = document.getElementById('priceSummary');
    const durText = document.getElementById('durationText');
    const totText = document.getElementById('totalText');
    const dailyRate = {{ $item->daily_rate }};

    function updateSummary() {
        if (startInput.value && endInput.value) {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                summary.classList.remove('hidden');
                durText.textContent = diffDays + ' hari';
                totText.textContent = 'Rp ' + (diffDays * dailyRate).toLocaleString('id-ID');
            } else {
                summary.classList.add('hidden');
            }
        } else {
            summary.classList.add('hidden');
        }
    }

    startInput.addEventListener('change', () => {
        endInput.min = startInput.value;
        updateSummary();
    });
    endInput.addEventListener('change', updateSummary);

    function validateDates() {
        if (!startInput.value || !endInput.value) {
            alert('⚠️ Silakan pilih tanggal mulai dan kembali');
            return false;
        }
        if (new Date(startInput.value) >= new Date(endInput.value)) {
            alert('⚠️ Tanggal kembali harus setelah tanggal mulai');
            return false;
        }
        return true;
    }

    // Set min date to today
    const today = new Date().toISOString().split('T')[0];
    startInput.min = today;
    endInput.min = today;
</script>
@endpush