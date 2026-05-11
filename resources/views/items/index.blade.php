@extends('layouts.app')
@section('title', 'Catalog - CampLans')

@push('styles')
<style>
    /* Premium Toggle Switch */
    .search-toggle {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 9999px;
        padding: 0.35rem;
        display: inline-flex;
        gap: 0.25rem;
    }
    .dark .search-toggle { background: rgba(30, 41, 59, 0.5); border-color: rgba(255,255,255,0.05); }
    .search-toggle button {
        padding: 0.5rem 1.5rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
    }
    .dark .search-toggle button { color: #94a3b8; }
    .search-toggle button.active {
        background: linear-gradient(135deg, #14b8a6, #0f766e);
        color: white;
        box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
    }
    .search-panel { display: none; animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .search-panel.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Professional Card */
    .product-card-pro {
        background: white;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .dark .product-card-pro { background: rgba(30, 41, 59, 0.7); border-color: rgba(255,255,255,0.05); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2); }
    .product-card-pro:hover {
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        transform: translateY(-8px);
    }
    .dark .product-card-pro:hover { box-shadow: 0 20px 40px -10px rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.1); }
    
    /* Skeleton Loading */
    .skeleton { 
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); 
        background-size: 200% 100%; 
        animation: shimmer 1.5s infinite; 
    }
    .dark .skeleton { background: linear-gradient(90deg, #334155 25%, #475569 50%, #334155 75%); background-size: 200% 100%; }
    @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    
    /* Hide scrollbar for chips */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-12">
    <!-- ✅ Breadcrumbs -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200/50 dark:border-gray-700/50 py-3 px-4">
        <div class="max-w-7xl mx-auto flex items-center text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-teal-600 transition">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white font-medium">Katalog</span>
        </div>
    </div>

    <!-- ✅ Sticky Search Panel (Glass) -->
    <div class="sticky top-16 z-30 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">CampLens</h1>
                
                <!-- ✅ Toggle Switch (Dipertahankan) -->
                <div class="search-toggle">
                    <button id="btn-date" class="active" onclick="switchSearch('date')">Cari Tanggal</button>
                    <button id="btn-item" onclick="switchSearch('item')">Cari Barang</button>
                </div>
            </div>

            <!-- Search by Date Panel -->
            <div id="panel-date" class="search-panel active">
                <form action="{{ route('items.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <input type="hidden" name="search_type" value="date">
                    <select name="category" class="flex-1 px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach($cats as $cat) <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option> @endforeach
                    </select>
                    <input type="date" name="start" value="{{ request('start') }}" class="flex-1 px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
                    <input type="date" name="end" value="{{ request('end') }}" class="flex-1 px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
                    <button type="submit" class="px-6 py-3 bg-teal-500 text-white rounded-xl font-semibold hover:bg-teal-600 transition shadow-md">CARI</button>
                </form>
            </div>

            <!-- Search by Item Panel -->
            <div id="panel-item" class="search-panel">
                <form action="{{ route('items.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <input type="hidden" name="search_type" value="item">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peralatan..." class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <select name="category" class="md:w-48 px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Kategori</option>
                        @foreach($cats as $cat) <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option> @endforeach
                    </select>
                    <button type="submit" class="px-6 py-3 bg-teal-500 text-white rounded-xl font-semibold hover:bg-teal-600 transition shadow-md">CARI</button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pt-6">
        <!-- ✅ Filter Chips (Kayak-style) -->
        <div class="flex flex-wrap gap-2 mb-8 overflow-x-auto hide-scrollbar pb-2">
            @php $chips = ['In Stock', 'Under 100k', 'Premium Gear']; @endphp
            @foreach($chips as $chip)
                <a href="{{ request()->fullUrlWithQuery(['filter' => $chip]) }}" 
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition whitespace-nowrap
                   {{ request('filter')==$chip ? 'bg-teal-500 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-teal-400' }}">
                    {{ str_replace(['In Stock', 'Under 100k', 'Premium Gear'], ['Tersedia', 'Di Bawah 100rb', 'Peralatan Premium'], $chip) }}
                </a>
            @endforeach
            @if(request('filter'))
                <a href="{{ route('items.index') }}" class="px-3 py-1.5 text-xs text-red-500 hover:underline font-medium self-center">✕ Hapus</a>
            @endif
        </div>

        <!-- ✅ Product Grid + Skeleton Loading -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($items as $item)
                <div class="product-card-pro group">
                    <div class="relative aspect-[4/3] bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <img src="{{ $item->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.src='https://placehold.co/400x300/e5e7eb/6b7280?text=IMG'">
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-bold text-white {{ $item->stock > 0 ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $item->stock > 0 ? 'Tersedia (' . $item->stock . ')' : 'Habis' }}
                        </span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $item->name }}</h3>
                        <div class="flex items-center gap-1 mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-yellow-400">★★★★☆</span> <span>({{ rand(15, 90) }})</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 mb-3 line-clamp-2">
                            {{ $item->category->name }} berkualitas tinggi untuk petualangan Anda.
                        </p>
                        <div class="flex items-end justify-between mt-4">
                            <div>
                                <p class="text-xs text-gray-400">per hari</p>
                                <p class="text-xl font-bold text-teal-600 dark:text-teal-400">Rp {{ number_format($item->daily_rate, 0, ',', '.') }}</p>
                            </div>
                            @auth
                                @if(auth()->user()->role === 'customer')
                                    <form method="POST" action="{{ route('cart.add') }}">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="start" value="{{ request('start') ?? now()->format('Y-m-d') }}">
                                        <input type="hidden" name="end" value="{{ request('end') ?? now()->addDay()->format('Y-m-d') }}">
                                        <button type="submit" class="bg-teal-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-teal-600 transition shadow-sm">Sewa</button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="bg-teal-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-teal-600 transition">Masuk</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <!-- Skeleton Loading State -->
                @for($i=0; $i<6; $i++)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden p-5 space-y-4">
                    <div class="aspect-[4/3] bg-gray-200 dark:bg-gray-700 rounded-xl skeleton"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-3/4 skeleton"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 skeleton"></div>
                    <div class="flex justify-between mt-4">
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-24 skeleton"></div>
                        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded-xl w-28 skeleton"></div>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchSearch(type) {
        const btnDate = document.getElementById('btn-date');
        const btnItem = document.getElementById('btn-item');
        const panelDate = document.getElementById('panel-date');
        const panelItem = document.getElementById('panel-item');
        if (type === 'date') {
            btnDate.classList.add('active'); btnItem.classList.remove('active');
            panelDate.classList.add('active'); panelItem.classList.remove('active');
        } else {
            btnItem.classList.add('active'); btnDate.classList.remove('active');
            panelItem.classList.add('active'); panelDate.classList.remove('active');
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        const today = new Date().toISOString().split('T')[0];
        const startInput = document.querySelector('input[name="start"]');
        const endInput = document.querySelector('input[name="end"]');
        if (startInput) { startInput.min = today; startInput.addEventListener('change', () => { if (endInput) endInput.min = startInput.value; }); }
        if (endInput) endInput.min = today;
    });
</script>
@endpush