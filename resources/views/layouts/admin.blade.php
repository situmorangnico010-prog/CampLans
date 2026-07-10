<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin - CampLens')</title>
    
    <!-- Premium Font: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            color: #1e293b;
        }
        .sidebar { 
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            width: 280px; 
            height: 100vh; 
            position: fixed; 
            left: 0; 
            top: 0; 
            padding: 2.5rem 1.25rem; 
            display: flex; 
            flex-direction: column; 
            border-right: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 50;
        }
        .main-content { margin-left: 280px; padding: 2.5rem; min-height: 100vh; }
        
        .nav-btn { 
            color: #64748b; 
            padding: 0.875rem 1.25rem; 
            border-radius: 1rem; 
            font-weight: 600; 
            font-size: 0.9rem; 
            margin-bottom: 0.5rem; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .nav-btn:hover { 
            background-color: #f1f5f9; 
            color: #0d9488;
            transform: translateX(4px);
        }
        .nav-btn.active { 
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); 
            color: white; 
            box-shadow: 0 10px 20px -5px rgba(13, 148, 136, 0.3);
        }
        
        .dashboard-card { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px); 
            border-radius: 2rem; 
            padding: 2rem; 
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); 
            border: 1px solid rgba(226, 232, 240, 0.5); 
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1);
            border-color: #0d9488;
        }

        .stat-card { 
            background: white; 
            border-radius: 1.5rem; 
            padding: 1.5rem; 
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .stat-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .stat-label { 
            font-size: 0.7rem; 
            font-weight: 800; 
            color: #94a3b8; 
            text-transform: uppercase; 
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
            display: block;
        }
        
        .banner-container {
            position: relative;
            border-radius: 2.5rem;
            overflow: hidden;
            margin-bottom: 2.5rem;
            height: 300px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2);
        }
        .banner-img { width: 100%; height: 100%; object-fit: cover; }
        .banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, transparent 60%);
            display: flex;
            align-items: center;
            padding: 3rem;
        }
        
        .logout-btn { 
            color: #ef4444; 
            font-weight: 700; 
            padding: 0.875rem 1.25rem; 
            border-radius: 1rem; 
            text-align: left; 
            margin-top: 0.5rem; 
            font-size: 0.9rem; 
            transition: all 0.3s ease;
        }
        .logout-btn:hover { background: #fef2f2; transform: translateX(4px); }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @stack('styles')
</head>
<body class="antialiased">

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="mb-10 flex justify-center">
            <a href="{{ route('home') }}" class="block">
                <img src="{{ asset('images/logo.png') }}" alt="CampLens" class="h-16 md:h-20 w-auto object-contain">
            </a>
        </div>

        <nav class="flex-1 flex flex-col">
            <a href="{{ route('admin.dashboard') }}" class="nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.items') }}" class="nav-btn {{ request()->routeIs('admin.items') ? 'active' : '' }}">Kelola Barang</a>
            <a href="{{ route('admin.categories') }}" class="nav-btn {{ request()->routeIs('admin.categories') ? 'active' : '' }}">Kelola Kategori</a>
            <a href="{{ route('admin.rentals') }}" class="nav-btn {{ request()->routeIs('admin.rentals') ? 'active' : '' }}">Kelola Sewa</a>
            <a href="{{ route('admin.payments') }}" class="nav-btn {{ request()->routeIs('admin.payments', 'admin.paymentDetail') ? 'active' : '' }}">
                Verifikasi Pembayaran
                @php $pendingPayments = \App\Models\Rental::where('transaction_status', 'waiting_verification')->count(); @endphp
                @if($pendingPayments > 0)
                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingPayments }}</span>
                @endif
            </a>
            
            <div class="mt-auto flex flex-col w-full">
                <a href="{{ route('admin.withdraw') }}" class="nav-btn {{ request()->routeIs('admin.withdraw') ? 'active' : '' }}">Laporan Pendapatan</a>
                <a href="{{ route('admin.paymentSettings') }}" class="nav-btn {{ request()->routeIs('admin.paymentSettings') ? 'active' : '' }}">Pembayaran Manual</a>
                <a href="{{ route('admin.settings') }}" class="nav-btn {{ request()->routeIs('admin.settings') ? 'active' : '' }}">Pengaturan</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn w-full">Logout</button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
