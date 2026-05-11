@extends('layouts.app')

@section('content')

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded-xl shadow">

        <h2 class="text-2xl font-bold text-center mb-5">Register</h2>

        {{-- ERROR --}}
        @if($errors->any())
            <div class="mb-3 text-red-500 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf

            <input type="text" name="name" placeholder="Nama"
                class="w-full mb-3 p-2 border rounded" required>

            <input type="email" name="email" placeholder="Email"
                class="w-full mb-3 p-2 border rounded" required>

            <input type="password" name="password" placeholder="Password"
                class="w-full mb-3 p-2 border rounded" required>

            <button class="w-full bg-green-600 text-white py-2 rounded mb-3 hover:bg-green-700">
                Daftar
            </button>

            <p class="text-center text-sm">
                Sudah punya akun?
                <a href="/login" class="text-blue-600">Login</a>
            </p>

        </form>

    </div>
</div>

@endsection