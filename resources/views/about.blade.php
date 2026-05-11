@extends('layouts.app')

@section('content')

<section class="max-w-screen-xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold text-center mb-6">Tentang CampLens</h1>

    <p class="text-center text-gray-600 mb-10">
        CampLens adalah platform rental kamera dan alat camping.
    </p>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-xl font-semibold mb-2">Visi</h3>
            <p>Menjadi platform terbaik.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-xl font-semibold mb-2">Misi</h3>
            <ul class="list-disc pl-5">
                <li>Alat berkualitas</li>
                <li>Harga terjangkau</li>
            </ul>
        </div>
    </div>
</section>

@endsection