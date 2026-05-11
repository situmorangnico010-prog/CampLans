<x-guest-layout>
    <div class="flex min-h-screen bg-white">
        <!-- Left Side: Image (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?q=80&w=2000&auto=format&fit=crop" 
                 alt="Reset Password" class="absolute inset-0 w-full h-full object-cover opacity-60">
            <div class="relative z-10 p-12 flex flex-col justify-center text-white w-full h-full bg-gradient-to-r from-slate-900/50 to-transparent">
                <h1 class="text-4xl font-bold mb-4 tracking-tight">Jangan Khawatir.</h1>
                <p class="text-lg text-slate-100/90 leading-relaxed max-w-md">Kami akan membantu Anda mendapatkan akses kembali ke akun Anda dengan aman.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-12 lg:px-24 py-12">
            <div class="max-w-md w-full mx-auto">
                <div class="mb-10">
                    <a href="/" class="inline-block">
                        <img src="{{ asset('images/logo.png') }}" alt="CampLens Logo" class="h-12 w-auto object-contain">
                    </a>
                </div>

                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Lupa Kata Sandi?</h2>
                <p class="text-slate-500 mb-8">
                    Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
                </p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none text-slate-900 placeholder:text-slate-400"
                               placeholder="nama@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <button type="submit" 
                            class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-teal-500/20 active:scale-[0.98]">
                        Kirim Tautan Atur Ulang
                    </button>
                </form>

                <p class="mt-10 text-center text-sm text-slate-500">
                    Ingat kata sandi Anda? 
                    <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700 transition-colors">
                        Kembali ke halaman masuk
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
