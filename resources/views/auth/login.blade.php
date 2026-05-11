<x-guest-layout>
    <div class="flex min-h-screen bg-white">
        <!-- Left Side: Image (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-teal-900">
            <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?q=80&w=2000&auto=format&fit=crop" 
                 alt="Camping" class="absolute inset-0 w-full h-full object-cover opacity-80">
            <div class="relative z-10 p-12 flex flex-col justify-start text-white w-full h-full bg-gradient-to-b from-teal-900/80 to-transparent">
                <div>
                    <h1 class="text-4xl font-bold mb-4 tracking-tight">Selamat Datang Kembali di CampLens.</h1>
                    <p class="text-lg text-teal-50/90 leading-relaxed max-w-md">Lanjutkan petualangan Anda dengan peralatan terbaik dari kami.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-12 lg:px-24 py-12">
            <div class="mb-10 lg:hidden flex justify-center">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="CampLens Logo" class="h-12 w-auto object-contain">
                </a>
            </div>

            <div class="max-w-md w-full mx-auto">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Masuk ke Akun Anda</h2>
                <p class="text-slate-500 mb-8">Selamat datang kembali! Silakan masukkan detail Anda.</p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none text-slate-900 placeholder:text-slate-400"
                               placeholder="nama@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors" href="{{ route('password.request') }}">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none text-slate-900 placeholder:text-slate-400"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" 
                                   class="w-4 h-4 rounded border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500">
                            <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-teal-500/20 active:scale-[0.98]">
                        Masuk Sekarang
                    </button>
                </form>

                <p class="mt-10 text-center text-sm text-slate-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-teal-600 hover:text-teal-700 transition-colors">
                        Daftar gratis sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
