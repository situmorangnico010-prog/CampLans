<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CampLens')</title>
    
    <!-- Premium Font: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(100%); } }
        .animate-slide-in { animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fade-out { animation: fadeOut 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Premium Glassmorphism */
        .glass { 
            background: rgba(255, 255, 255, 0.7); 
            backdrop-filter: blur(24px) saturate(180%); 
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.1);
        }
        .dark .glass {
            background: rgba(17, 24, 39, 0.7);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.3);
        }
        .glass-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .glass-hover:hover { background: rgba(0, 0, 0, 0.04); transform: translateY(-1px); }
        .dark .glass-hover:hover { background: rgba(255, 255, 255, 0.08); }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-900 dark:to-gray-950 text-slate-800 dark:text-slate-100 antialiased min-h-screen transition-colors duration-500">

    <!-- Toast Notifications -->
    @if(session('success'))
        <div id="toast-success" class="fixed top-4 right-4 z-50 glass rounded-xl p-4 flex items-center gap-3 max-w-sm animate-slide-in">
            <span class="text-xl">✅</span><span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="dismissToast(this.parentElement)" class="ml-auto text-slate-400 hover:text-slate-600 transition-colors">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div id="toast-error" class="fixed top-4 right-4 z-50 glass rounded-xl p-4 flex items-center gap-3 max-w-sm animate-slide-in border-l-4 border-red-500">
            <span class="text-xl">❌</span><span class="text-sm font-medium">{{ session('error') }}</span>
            <button onclick="dismissToast(this.parentElement)" class="ml-auto text-slate-400 hover:text-slate-600 transition-colors">&times;</button>
        </div>
    @endif

    <!-- Navbar -->
    <nav class="sticky top-0 z-40 glass border-b border-white/20 dark:border-white/5 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center hover:opacity-80 transition-opacity">
                <img src="{{ asset('images/logo.png') }}" alt="CampLens Logo" class="h-12 md:h-16 object-contain scale-110 origin-left">
            </a>

            <div class="hidden md:flex items-center gap-6">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('items.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-teal-500 font-medium transition-colors">Katalog</a>
                    @endif
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-600 dark:text-slate-300 hover:text-teal-500 font-medium transition-colors">Dashboard</a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center gap-3">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="p-2 text-slate-600 dark:text-slate-300 hover:text-teal-500 transition-colors focus:outline-none">
                    <!-- Sun Icon (Show in Dark Mode) -->
                    <svg x-show="darkMode" class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M3 12h2.25m.386-6.364l1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12a6.75 6.75 0 1113.5 0 6.75 6.75 0 01-13.5 0z"></path>
                    </svg>
                    <!-- Moon Icon (Show in Light Mode) -->
                    <svg x-show="!darkMode" class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"></path>
                    </svg>
                </button>

                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600 dark:text-slate-300 hover:text-teal-500 transition-colors focus:outline-none">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.076.721-.506 1.368-1.231 1.368H4.862c-.725 0-1.307-.647-1.231-1.368l1.263-12c.076-.721.727-1.275 1.455-1.275h11.28c.728 0 1.379.554 1.455 1.275z"></path>
                            </svg>
                            @if(session('cart.items') && count(session('cart.items') ?? []) > 0)
                                <span class="absolute top-0.5 right-0.5 bg-teal-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-1 shadow-sm">{{ array_sum(session('cart.items')) }}</span>
                            @endif
                        </a>
                    @endif

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-slate-600 dark:text-slate-300 hover:text-teal-500 transition-colors focus:outline-none">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2" 
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                             x-transition:leave="transition ease-in duration-150" 
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2" 
                             class="absolute right-0 mt-3 w-64 rounded-2xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50" style="display: none;">
                            
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('account.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Akun Saya</a>

                                @if(auth()->user()->role === 'customer')
                                <a href="{{ route('rentals.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Pesanan</a>
                                @endif

                                @if(auth()->user()->role === 'customer')
                                <a href="{{ route('help') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Pusat Bantuan</a>
                                @endif
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-slate-600 dark:text-slate-400 hover:text-teal-500 font-medium transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="text-sm bg-gradient-to-r from-teal-500 to-teal-600 text-white px-5 py-2 rounded-xl font-medium hover:from-teal-600 hover:to-teal-700 shadow-md shadow-teal-500/20 transition-all hover:-translate-y-0.5">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="glass border-t border-white/20 dark:border-white/5 py-8 mt-16 text-center text-slate-500 dark:text-slate-400 text-sm">
        © {{ date('Y') }} CampLens. Sewa kamera & alat camping profesional.
    </footer>

    <script>
        function dismissToast(el) {
            el.classList.remove('animate-slide-in');
            el.classList.add('animate-fade-out');
            setTimeout(() => el.remove(), 400);
        }
        document.addEventListener('DOMContentLoaded', () => {
            ['toast-success', 'toast-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) setTimeout(() => dismissToast(el), 4000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>