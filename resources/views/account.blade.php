@extends('layouts.app')
@section('title', 'Pengaturan Akun - CampLens')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Pengaturan Akun</h1>

        <!-- Main Profile Card -->
        <div class="glass-card rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md mb-6">
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-200/50 dark:border-gray-700/50">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 text-white flex items-center justify-center text-2xl font-bold shadow-md ring-4 ring-white/50 dark:ring-gray-700/50">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold 
                        {{ auth()->user()->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400' }}">
                        {{ auth()->user()->role === 'admin' ? '👑 Administrator' : '👤 Pelanggan' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('account.update') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none text-gray-900 dark:text-white transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled 
                           class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah untuk keamanan akun.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" 
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none text-gray-900 dark:text-white transition">
                    <p class="text-xs text-gray-400 mt-1">Minimal 6 karakter.</p>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-teal-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-teal-600 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Role-Specific Quick Stats -->
        @if(auth()->user()->role === 'customer')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $rentals = auth()->user()->rentals;
                $total = $rentals->count();
                $active = $rentals->where('status', 'active')->count();
                $completed = $rentals->where('status', 'completed')->count();
            @endphp
            <div class="glass-card rounded-xl p-5 border border-gray-100 dark:border-gray-700 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md text-center">
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $total }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Sewa</p>
            </div>
            <div class="glass-card rounded-xl p-5 border border-gray-100 dark:border-gray-700 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md text-center">
                <p class="text-3xl font-bold text-teal-600 dark:text-teal-400">{{ $active }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Sedang Aktif</p>
            </div>
            <div class="glass-card rounded-xl p-5 border border-gray-100 dark:border-gray-700 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md text-center">
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $completed }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
            </div>
        </div>
        @endif

        @if(auth()->user()->role === 'admin')
        <div class="glass-card rounded-xl p-5 border border-purple-200 dark:border-purple-800 bg-purple-50/50 dark:bg-purple-900/10 backdrop-blur-md flex items-center gap-4">
            <span class="text-2xl">👑</span>
            <div>
                <p class="font-semibold text-purple-900 dark:text-purple-300">Mode Administrator Aktif</p>
                <p class="text-sm text-purple-700 dark:text-purple-400">Anda memiliki akses penuh ke dashboard manajemen, approval sewa, dan manajemen inventaris.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection