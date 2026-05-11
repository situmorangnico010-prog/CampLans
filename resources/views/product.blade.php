<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampLens - Sewa Kamera & Camping</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white shadow-sm border-b">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">CampLens</h1>

        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">
                <i class="fas fa-phone mr-1"></i> 087829988870
            </span>

            <a href="/login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                Login
            </a>
        </div>
    </div>
</header>

<!-- NAVBAR -->
<nav class="bg-gray-800 text-white">
    <div class="container mx-auto px-4">
        <ul class="flex space-x-6 py-3">
            <li><a href="/" class="hover:text-blue-300">Home</a></li>
            <li><a href="/about" class="hover:text-blue-300">About</a></li>
            <li><a href="/produk" class="hover:text-blue-300">Produk</a></li>
        </ul>
    </div>
</nav>

<!-- CONTENT -->
<main class="flex-grow container mx-auto px-4 py-8">

    <h2 class="text-2xl font-bold mb-2">Produk Tersedia</h2>
    <p class="text-gray-600 mb-6">Sewa kamera & alat camping terbaik</p>

    <!-- FILTER -->
    <div class="mb-6 border-b">
        <button id="all-tab" class="px-4 py-2 border-b-2 border-blue-500 text-blue-500">Semua</button>
        <button id="camera-tab" class="px-4 py-2">Kamera</button>
        <button id="camping-tab" class="px-4 py-2">Camping</button>
    </div>

    <!-- PRODUK -->
    <div class="grid md:grid-cols-3 gap-6">

        <!-- CAMERA -->
        <div class="product-card camera bg-white p-4 rounded shadow">
            <h3 class="font-bold">Canon EOS R5</h3>
            <p class="text-gray-600">Rp 500.000 / hari</p>
            <button class="mt-2 bg-blue-500 text-white px-3 py-1 rounded">Sewa</button>
        </div>

        <div class="product-card camera bg-white p-4 rounded shadow">
            <h3 class="font-bold">Sony A7 IV</h3>
            <p class="text-gray-600">Rp 450.000 / hari</p>
            <button class="mt-2 bg-blue-500 text-white px-3 py-1 rounded">Sewa</button>
        </div>

        <!-- CAMPING -->
        <div class="product-card camping bg-white p-4 rounded shadow">
            <h3 class="font-bold">Tenda Dome</h3>
            <p class="text-gray-600">Rp 200.000 / hari</p>
            <button class="mt-2 bg-blue-500 text-white px-3 py-1 rounded">Sewa</button>
        </div>

        <div class="product-card camping bg-white p-4 rounded shadow">
            <h3 class="font-bold">Sleeping Bag</h3>
            <p class="text-gray-600">Rp 75.000 / hari</p>
            <button class="mt-2 bg-blue-500 text-white px-3 py-1 rounded">Sewa</button>
        </div>

    </div>

</main>

<!-- FOOTER -->
<footer class="bg-gray-800 text-white text-center py-3">
    © 2026 CampLens - Nico Project
</footer>

<!-- SCRIPT FILTER -->
<script>
document.getElementById('all-tab').onclick = () => show('all');
document.getElementById('camera-tab').onclick = () => show('camera');
document.getElementById('camping-tab').onclick = () => show('camping');

function show(type) {
    document.querySelectorAll('.product-card').forEach(el => el.style.display = 'none');

    if(type === 'all') {
        document.querySelectorAll('.product-card').forEach(el => el.style.display = 'block');
    } else {
        document.querySelectorAll('.' + type).forEach(el => el.style.display = 'block');
    }
}
</script>

</body>
</html>
