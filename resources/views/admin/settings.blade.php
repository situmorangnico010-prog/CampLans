@extends('layouts.admin')
@section('title', 'Pengaturan Akun - CampLens')

@section('content')
<div class="mb-8 text-center">
    <h1 class="text-2xl font-bold">Pengaturan Akun</h1>
    <p class="text-sm text-gray-500">Kelola informasi profil dan keamanan akun admin Anda</p>
</div>

@if(session('success'))
    <div class="bg-teal-100 border border-teal-400 text-teal-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-2xl mx-auto">
    <div class="dashboard-card">
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
            <div class="w-16 h-16 bg-teal-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-teal-500/30">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h3>
                <p class="text-sm text-teal-600 font-medium uppercase tracking-wider">Administrator</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.updateSettings') }}">
            @csrf
            <div class="space-y-6">
                <!-- Informasi Profil -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required 
                               class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required 
                               class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 focus:bg-white transition">
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Ubah Kata Sandi (Kosongkan jika tidak ingin mengubah)</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700">Kata Sandi Baru</label>
                            <input type="password" name="password" minlength="8"
                                   class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 focus:bg-white transition"
                                   placeholder="Minimal 8 karakter">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" minlength="8"
                                   class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 focus:bg-white transition"
                                   placeholder="Ulangi kata sandi">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-teal-600 text-white py-4 rounded-2xl font-bold hover:bg-teal-700 transition shadow-lg shadow-teal-500/30 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan Profil
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Keamanan -->
    <div class="mt-8 p-6 bg-yellow-50 border border-yellow-100 rounded-3xl flex items-start gap-4">
        <div class="w-10 h-10 bg-yellow-400 text-white rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
            <h4 class="font-bold text-yellow-800 text-sm mb-1">Tips Keamanan</h4>
            <p class="text-xs text-yellow-700 leading-relaxed">Pastikan kata sandi Anda terdiri dari minimal 8 karakter dengan kombinasi huruf dan angka untuk menjaga keamanan akun Administrator CampLens.</p>
        </div>
    </div>
</div>
@endsection
