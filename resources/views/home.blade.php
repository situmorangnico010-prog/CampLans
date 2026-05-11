@extends('layouts.app')
@section('title', 'CampLens - Sewa Kamera & Camping Profesional')

@push('styles')
<style>
    /* Premium Hero Slider */
    .hero-slider { position: relative; height: 550px; overflow: hidden; border-radius: 0 0 2rem 2rem; }
    .slide {
        position: absolute; inset: 0; background-size: cover; background-position: center;
        opacity: 0; transition: opacity 2s ease-in-out, transform 12s linear; transform: scale(1.05);
    }
    .slide::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0.8) 100%);
    }
    .slide.active { opacity: 1; transform: scale(1); }

    /* Hide Scrollbar */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* Glass Inputs */
    .glass-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        backdrop-filter: blur(12px);
        transition: all 0.3s ease;
    }
    .glass-input::placeholder { color: rgba(255, 255, 255, 0.6); }
    .glass-input:focus { background: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.5); outline: none; box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1); }
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; opacity: 0.7; transition: opacity 0.3s; }
    input[type="date"]::-webkit-calendar-picker-indicator:hover { opacity: 1; }
    input[type="date"] { color-scheme: dark; }

    /* Responsive Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1.5rem;
    }
    @media (min-width: 640px) { .product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1024px) { .product-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    @media (min-width: 1280px) { .product-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }

    .product-card {
        background: white; border-radius: 1.5rem; overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(0,0,0,0.05); height: 100%;
    }
    .dark .product-card { background: rgba(30, 41, 59, 0.7); border-color: rgba(255,255,255,0.05); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2); }
    .product-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1); }
    .dark .product-card:hover { box-shadow: 0 20px 40px -10px rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1); }
    .product-card img { width: 100%; height: 220px; object-fit: cover; transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
    .product-card:hover img { transform: scale(1.08); }

    /* Marquee Animation */
    .marquee-wrapper {
        display: flex;
        overflow: hidden;
        user-select: none;
        width: 100%;
        mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }
    .marquee-content {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: space-around;
        gap: 4rem;
        padding-right: 4rem;
        animation: scroll 30s linear infinite;
    }
    .marquee-content-reverse {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: space-around;
        gap: 4rem;
        padding-right: 4rem;
        animation: scroll-reverse 30s linear infinite;
    }
    .marquee-wrapper:hover .marquee-content,
    .marquee-wrapper:hover .marquee-content-reverse {
        animation-play-state: paused;
    }
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }
    @keyframes scroll-reverse {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(0); }
    }
</style>
@endpush

@section('content')
<!-- Premium Hero Section -->
<section class="hero-slider shadow-2xl mx-2 mt-2 md:mx-4 md:mt-4">
    <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=1600&q=80')"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=1600&q=80')"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=1600&q=80')"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 pt-24 pb-12 flex flex-col items-center justify-center h-full">
        <div class="text-center mb-10 max-w-3xl animate-slide-in">
            @auth 
                <span class="inline-block py-1.5 px-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white/90 text-sm font-medium mb-6">
                    👋 Selamat datang kembali, {{ explode(' ', auth()->user()->name)[0] }}
                </span>
            @else 
                <span class="inline-block py-1.5 px-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white/90 text-sm font-medium mb-6">
                    ✨ Abadikan Perjalanan Anda
                </span>
            @endauth
            <h1 class="text-4xl md:text-6xl font-extrabold text-white drop-shadow-xl leading-tight tracking-tight">
                Persiapkan <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-emerald-300">Petualangan Anda</span>
            </h1>
        </div>

        <!-- Glass Search Panel -->
        <div class="glass rounded-3xl p-6 md:p-8 max-w-4xl w-full mx-auto animate-slide-in" style="animation-delay: 0.1s; border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.15);">
            <div class="flex gap-3 mb-6 overflow-x-auto scrollbar-hide pb-2">
                <a href="{{ route('items.index', ['category' => 1]) }}" class="px-6 py-2.5 bg-white text-teal-600 rounded-full font-bold text-sm whitespace-nowrap shadow-lg hover:scale-105 transition-transform inline-block">KAMERA</a>
                <a href="{{ route('items.index', ['category' => 2]) }}" class="px-6 py-2.5 bg-white/10 text-white rounded-full font-medium text-sm whitespace-nowrap border border-white/20 hover:bg-white/20 transition-colors inline-block">ALAT KEMAH</a>
            </div>

            <form action="{{ route('items.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" placeholder="Apa yang Anda cari?" class="w-full pl-12 pr-5 py-4 rounded-2xl glass-input text-base font-medium">
                </div>
                <div class="flex gap-4 flex-1">
                    <input type="date" name="start" class="flex-1 px-5 py-4 rounded-2xl glass-input text-sm font-medium" required>
                    <input type="date" name="end" class="flex-1 px-5 py-4 rounded-2xl glass-input text-sm font-medium" required>
                </div>
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-2xl font-bold hover:from-teal-400 hover:to-teal-500 transition-all shadow-[0_0_20px_rgba(20,184,166,0.4)] flex items-center justify-center hover:shadow-[0_0_25px_rgba(20,184,166,0.6)] hover:-translate-y-0.5 whitespace-nowrap">
                    Cari Peralatan
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Brands Marquee Section -->
<section class="py-12 bg-slate-50 dark:bg-slate-900/50 overflow-hidden relative border-b border-slate-200 dark:border-slate-800">
    <div class="mx-2 md:mx-4">
        <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8 relative overflow-hidden backdrop-blur-sm">
            <!-- Camera Brands Marquee (Scrolls Left) -->
            <div class="marquee-wrapper mb-10">
                <div class="marquee-content">
                    @for($i = 0; $i < 3; $i++)
                    <img src="https://cdn.simpleicons.org/sony" alt="Sony" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://raw.githubusercontent.com/detain/svg-logos/master/svg/f/fujifilm.svg" alt="Fujifilm" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://myid.canon/prd/1.1.38.1/canonid-assets/svg/Canon.svg" alt="Canon" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://raw.githubusercontent.com/detain/svg-logos/master/svg/n/nikon.svg" alt="Nikon" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/dji" alt="DJI" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/panasonic" alt="Panasonic" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://raw.githubusercontent.com/detain/svg-logos/master/svg/l/leica-1.svg" alt="Leica" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/kodak" alt="Kodak" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/40/R%C3%98DE_logo.svg" alt="Rode" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/sennheiser" alt="Sennheiser" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    @endfor
                </div>
                <div class="marquee-content" aria-hidden="true">
                    @for($i = 0; $i < 3; $i++)
                    <img src="https://cdn.simpleicons.org/sony" alt="Sony" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://raw.githubusercontent.com/detain/svg-logos/master/svg/f/fujifilm.svg" alt="Fujifilm" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://myid.canon/prd/1.1.38.1/canonid-assets/svg/Canon.svg" alt="Canon" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://raw.githubusercontent.com/detain/svg-logos/master/svg/n/nikon.svg" alt="Nikon" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/dji" alt="DJI" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/panasonic" alt="Panasonic" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://raw.githubusercontent.com/detain/svg-logos/master/svg/l/leica-1.svg" alt="Leica" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/kodak" alt="Kodak" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/40/R%C3%98DE_logo.svg" alt="Rode" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/sennheiser" alt="Sennheiser" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    @endfor
                </div>
            </div>

            <!-- Camping Gear Brands Marquee (Scrolls Right) -->
            <div class="marquee-wrapper">
                <div class="marquee-content-reverse">
                    @for($i = 0; $i < 6; $i++)
                    <img src="https://cdn.simpleicons.org/thenorthface" alt="The North Face" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/garmin" alt="Garmin" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/Stanley_Hand_Tools_logo.svg" alt="Stanley" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/bf/YETI_Holdings_logo.svg" alt="YETI" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b3/Marmot_company_logo.svg" alt="Marmot" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    @endfor
                </div>
                <div class="marquee-content-reverse" aria-hidden="true">
                    @for($i = 0; $i < 6; $i++)
                    <img src="https://cdn.simpleicons.org/thenorthface" alt="The North Face" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://cdn.simpleicons.org/garmin" alt="Garmin" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/Stanley_Hand_Tools_logo.svg" alt="Stanley" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/bf/YETI_Holdings_logo.svg" alt="YETI" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b3/Marmot_company_logo.svg" alt="Marmot" class="h-6 md:h-8 w-auto object-contain opacity-80 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trending Deals Section -->
<section class="py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Peralatan Terpopuler</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2">Peralatan dengan rating tertinggi untuk liburan Anda.</p>
            </div>
            <a href="{{ route('items.index') }}" class="hidden sm:flex items-center gap-2 text-teal-600 dark:text-teal-400 font-semibold hover:text-teal-700 transition-colors">
                Lihat semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <div class="product-grid">
            @forelse($items->take(12) as $item)
                <a href="{{ route('items.show', $item->id) }}" class="product-card group relative flex flex-col">
                    <span class="absolute top-4 left-4 z-10 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide text-white shadow-md {{ $item->stock > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}">
                        {{ $item->stock > 0 ? 'Tersedia (' . $item->stock . ')' : 'Habis' }}
                    </span>
                    <div class="overflow-hidden relative">
                        <img src="{{ $item->image_url }}" class="w-full h-full object-cover" 
                             onerror="this.src='https://placehold.co/280x220/e2e8f0/64748b?text=Image+Not+Found'">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-5 flex flex-col flex-1 justify-between">
                        <div>
                            <p class="font-bold text-lg text-slate-900 dark:text-white truncate" title="{{ $item->name }}">{{ $item->name }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                {{ $item->category_id == 1 ? 'Kamera Profesional' : 'Alat Camping' }}
                            </p>
                        </div>
                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-semibold tracking-wider mb-0.5">Harga Harian</p>
                                <p class="text-teal-600 dark:text-teal-400 font-extrabold text-lg">Rp{{ number_format($item->daily_rate, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-md">
                                <span class="text-amber-400 text-sm">★</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 ml-1">4.9</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="w-full py-12 text-center text-slate-500 dark:text-slate-400">
                    <div class="text-4xl mb-4">📸</div>
                    <p>Tidak ada barang untuk kategori ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Trust & Policy Section -->
<section class="py-20 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-4">Mengapa Menyewa di CampLens?</h2>
            <p class="text-slate-500 dark:text-slate-400">Kami menyediakan peralatan terbaik tanpa repot sehingga Anda bisa fokus menciptakan kenangan.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="p-8 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-shadow duration-300 text-center group">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-teal-400 to-emerald-500 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-lg shadow-teal-500/30 group-hover:scale-110 transition-transform duration-300">🛡️</div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Perlindungan Asuransi</h3>
                <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Perlindungan penuh untuk kerusakan tak terduga & kehilangan selama masa sewa Anda.</p>
            </div>
            <div class="p-8 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-shadow duration-300 text-center group">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">⏱️</div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Pengembalian Fleksibel</h3>
                <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Nikmati masa tenggang 2 jam dengan kebijakan denda keterlambatan yang transparan.</p>
            </div>
            <div class="p-8 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-shadow duration-300 text-center group">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">🔒</div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Pemesanan Aman</h3>
                <p class="text-slate-500 dark:text-slate-400 leading-relaxed">Data terenkripsi penuh & konfirmasi pemesanan otomatis secara instan.</p>
            </div>
        </div>
    </div>
    <!-- Meet the Developers Section -->
    <section class="py-24 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">Tim Pengembang</h2>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-16 italic">
                "Mendedikasikan kreativitas dan keahlian untuk memberikan pengalaman sewa peralatan terbaik bagi Anda."
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Developer 1 -->
                <div class="group">
                    <div class="relative inline-block mb-6">
                        <div class="absolute inset-0 bg-teal-500 rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-300 opacity-20"></div>
                        <img src="https://ui-avatars.com/api/?name=Nicolas+Situmorang&background=0D9488&color=fff&size=128" 
                             alt="Nicolas Situmorang" 
                             class="relative z-10 w-32 h-32 rounded-2xl object-cover shadow-xl grayscale group-hover:grayscale-0 transition-all duration-500 bg-white">
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">Nicolas Situmorang</h3>
                    <p class="text-teal-600 dark:text-teal-400 font-semibold text-sm uppercase tracking-wider">Lead Developer</p>
                </div>

                <!-- Developer 2 -->
                <div class="group">
                    <div class="relative inline-block mb-6">
                        <div class="absolute inset-0 bg-teal-500 rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-300 opacity-20"></div>
                        <img src="https://ui-avatars.com/api/?name=M+Rakha+Aqil+Syafiq&background=0D9488&color=fff&size=128" 
                             alt="M. Rakha Aqil Syafiq" 
                             class="relative z-10 w-32 h-32 rounded-2xl object-cover shadow-xl grayscale group-hover:grayscale-0 transition-all duration-500 bg-white">
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">M. Rakha Aqil Syafiq</h3>
                    <p class="text-teal-600 dark:text-teal-400 font-semibold text-sm uppercase tracking-wider">UI/UX Designer</p>
                </div>

                <!-- Developer 3 -->
                <div class="group">
                    <div class="relative inline-block mb-6">
                        <div class="absolute inset-0 bg-teal-500 rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-300 opacity-20"></div>
                        <img src="https://ui-avatars.com/api/?name=Dzakwan+Fahrezi&background=0D9488&color=fff&size=128" 
                             alt="Dzakwan Fahrezi" 
                             class="relative z-10 w-32 h-32 rounded-2xl object-cover shadow-xl grayscale group-hover:grayscale-0 transition-all duration-500 bg-white">
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">Dzakwan Fahrezi</h3>
                    <p class="text-teal-600 dark:text-teal-400 font-semibold text-sm uppercase tracking-wider">Backend Developer</p>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let current = 0; 
        const slides = document.querySelectorAll('.slide');
        if(slides.length > 0) {
            setInterval(() => { 
                slides[current].classList.remove('active'); 
                current = (current + 1) % slides.length; 
                slides[current].classList.add('active'); 
            }, 6000);
        }
        
        // Auto-fill dates
        const startInput = document.querySelector('input[name="start"]');
        const endInput = document.querySelector('input[name="end"]');
        if(startInput && endInput) {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            startInput.value = today.toISOString().split('T')[0];
            endInput.value = tomorrow.toISOString().split('T')[0];
        }
    });
</script>
@endpush