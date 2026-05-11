@extends('layouts.app')

@section('content')

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded-xl shadow">

        <h2 class="text-2xl font-bold text-center mb-5">Login</h2>

        {{-- ERROR MESSAGE --}}
        @if(session('error'))
            <div class="mb-3 text-red-500 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <input type="email" name="email" placeholder="Email"
                class="w-full mb-3 p-2 border rounded" required>

            <input type="password" name="password" placeholder="Password"
                class="w-full mb-3 p-2 border rounded" required>

            <button class="w-full bg-blue-600 text-white py-2 rounded mb-3 hover:bg-blue-700">
                Login
            </button>

            <p class="text-center text-sm">
                Belum punya akun?
                <a href="/register" class="text-blue-600">Register</a>
            </p>

        </form>

    </div>
</div>

@endsection