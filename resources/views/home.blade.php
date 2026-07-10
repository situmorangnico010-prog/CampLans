<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampLens - Home</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-gray-900 border-gray-700">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/" class="text-white text-2xl font-bold">CampLens</a>

    <div class="flex space-x-4">
      <a href="/" class="text-white bg-blue-600 px-4 py-2 rounded-lg">Home</a>
      <a href="/about" class="text-gray-300 hover:text-white px-4 py-2">About</a>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="text-center py-16 bg-white shadow">
    <h1 class="text-4xl font-bold mb-4">Selamat Datang di CampLens</h1>
    <p class="text-gray-600 mb-6">Sewa Kamera & Alat Camping dengan Mudah</p>
    <a href="#" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
        Mulai Sewa
    </a>
</section>

<!-- Produk -->
<section class="max-w-screen-xl mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold text-center mb-8">Produk Kami</h2>

    <div class="grid md:grid-cols-3 gap-6">

        <!-- Card -->
        <div class="bg-white rounded-xl shadow p-4">
            <img src="https://via.placeholder.com/300x200" class="rounded-lg mb-4">
            <h3 class="text-lg font-semibold">Kamera DSLR</h3>
            <p class="text-gray-500 text-sm mb-3">Kamera berkualitas tinggi</p>
            <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                Sewa
            </button>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <img src="https://via.placeholder.com/300x200" class="rounded-lg mb-4">
            <h3 class="text-lg font-semibold">Tenda Camping</h3>
            <p class="text-gray-500 text-sm mb-3">Nyaman untuk outdoor</p>
            <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                Sewa
            </button>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <img src="https://via.placeholder.com/300x200" class="rounded-lg mb-4">
            <h3 class="text-lg font-semibold">Tas Carrier</h3>
            <p class="text-gray-500 text-sm mb-3">Kuat & ringan</p>
            <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                Sewa
            </button>
        </div>

    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white text-center py-4">
    <p>&copy; 2026 CampLens</p>
</footer>

<!-- Flowbite JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</body>
</html>