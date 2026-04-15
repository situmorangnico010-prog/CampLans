@extends('layouts.app')

@section('content')

<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded-xl shadow">

        <h2 class="text-2xl font-bold text-center mb-5">Login</h2>

        <form action="/login" method="POST">
            @csrf

            <input type="email" name="email" placeholder="Email"
                class="w-full mb-3 p-2 border rounded">

            <input type="password" name="password" placeholder="Password"
                class="w-full mb-3 p-2 border rounded">

            <button class="w-full bg-blue-600 text-white py-2 rounded mb-3">
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