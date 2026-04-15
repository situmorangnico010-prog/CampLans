<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampLens</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet"/>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

<!-- Navbar -->
<nav class="bg-gray-900 text-white">
    <div class="max-w-screen-xl mx-auto flex justify-between items-center p-4">

        <!-- Logo -->
        <a href="/" class="text-xl font-bold">CampLens</a>

        <!-- Menu -->
        <div class="space-x-4 flex items-center">
            <a href="/" class="hover:text-blue-400">Home</a>
            <a href="/products" class="hover:text-blue-400">Produk</a>
            <a href="/contact" class="hover:text-blue-400">Contact</a>

            <!-- Cart -->
            <a href="/cart" class="relative">
                🛒
                @if(session('cart'))
                <span class="absolute -top-2 -right-3 bg-red-500 text-xs px-2 rounded-full">
                    {{ count(session('cart')) }}
                </span>
                @endif
            </a>

            <!-- Auth -->
            <a href="/login" class="bg-blue-600 px-3 py-1 rounded">Login</a>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="p-6 flex-grow">
    @yield('content')
</div>

<!-- Footer -->
<footer class="bg-gray-900 text-white text-center py-4">
    <p>&copy; 2026 CampLens</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</body>
</html>