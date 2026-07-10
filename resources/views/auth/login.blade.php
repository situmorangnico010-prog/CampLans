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
                    <div x-data="{ showPass: false }">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors" href="{{ route('password.request') }}">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                   class="w-full px-4 py-3 pr-12 rounded-xl border border-slate-200 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none text-slate-900 placeholder:text-slate-400"
                                   placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                                <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
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
