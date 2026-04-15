<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - CampLens</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet"/>
</head>

<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-gray-900 p-4 text-white flex justify-between">
    <h1 class="font-bold">Dashboard CampLens</h1>
    <a href="/" class="bg-red-500 px-3 py-1 rounded">Logout</a>
</nav>

<div class="max-w-6xl mx-auto py-10">

    <h2 class="text-2xl font-bold mb-6">Dashboard Admin</h2>

    <div class="grid md:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h3 class="text-lg font-bold">Total Produk</h3>
            <p class="text-2xl text-blue-600 mt-2">12</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h3 class="text-lg font-bold">Total User</h3>
            <p class="text-2xl text-green-600 mt-2">8</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h3 class="text-lg font-bold">Transaksi</h3>
            <p class="text-2xl text-red-600 mt-2">5</p>
        </div>

    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>