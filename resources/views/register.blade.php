@extends('layouts.app')

@section('content')

<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded-xl shadow">

        <h2 class="text-2xl font-bold text-center mb-5">Register</h2>

        <form action="/register" method="POST">
            @csrf

            <input type="text" name="name" placeholder="Nama"
                class="w-full mb-3 p-2 border rounded">

            <input type="email" name="email" placeholder="Email"
                class="w-full mb-3 p-2 border rounded">

            <input type="password" name="password" placeholder="Password"
                class="w-full mb-3 p-2 border rounded">

            <button class="w-full bg-green-600 text-white py-2 rounded mb-3">
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