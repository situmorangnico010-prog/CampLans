<x-guest-layout>
    <div class="flex min-h-screen bg-white">
        <!-- Left Side: Image (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=2000&auto=format&fit=crop" 
                 alt="Photography" class="absolute inset-0 w-full h-full object-cover opacity-70">
            <div class="relative z-10 p-12 flex flex-col justify-start text-white w-full h-full bg-gradient-to-b from-slate-900/80 to-transparent">
                <div>
                    <h1 class="text-4xl font-bold mb-4 tracking-tight">Abadikan Setiap Momen Petualangan.</h1>
                    <p class="text-lg text-slate-100/90 leading-relaxed max-w-md">Daftar sekarang dan nikmati akses eksklusif ke peralatan kamera dan camping premium.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-12 lg:px-24 py-12">
            <div class="mb-10 lg:hidden flex justify-center">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="CampLens Logo" class="h-12 w-auto object-contain">
                </a>
            </div>

            <div class="max-w-md w-full mx-auto">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Buat Akun Baru</h2>
                <p class="text-slate-500 mb-8">Mulai perjalanan Anda dengan CampLens hari ini.</p>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none text-slate-900 placeholder:text-slate-400"
                               placeholder="Masukkan nama lengkap Anda">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none text-slate-900 placeholder:text-slate-400"
                               placeholder="nama@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Kata Sandi</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none text-slate-900 placeholder:text-slate-400"
                               placeholder="Minimal 8 karakter">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none text-slate-900 placeholder:text-slate-400"
                               placeholder="Ulangi kata sandi Anda">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-teal-500/20 active:scale-[0.98]">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm text-slate-500">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700 transition-colors">
                        Masuk ke akun Anda
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
