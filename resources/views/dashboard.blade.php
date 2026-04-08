<?php
// Dummy data barang
$barang = [
    ["nama" => "Canon EOS 80D", "harga" => "Rp150.000 / hari"],
    ["nama" => "Sony A6400", "harga" => "Rp180.000 / hari"],
    ["nama" => "Tenda The North Face", "harga" => "Rp100.000 / hari"],
    ["nama" => "Sleeping Bag Outdoor", "harga" => "Rp50.000 / hari"]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">

<!-- NAVBAR -->
<nav class="bg-white border-b shadow-sm">
    <div class="max-w-screen-xl mx-auto p-4 flex justify-between">
        <h1 class="text-xl font-bold">RentalApp</h1>
        <div>
            <a href="#" class="mr-4">Home</a>
            <a href="#" class="mr-4">Kategori</a>
            <a href="#" class="mr-4">Riwayat</a>
            <a href="login.php" class="text-red-500">Logout</a>
        </div>
    </div>
</nav>

<!-- BANNER -->
<div class="bg-blue-600 text-white text-center py-10">
    <h2 class="text-3xl font-bold">Sewa Kamera & Alat Camping</h2>
    <p class="mt-2">Mudah, cepat, dan terpercaya</p>
</div>

<!-- PRODUK -->
<div class="max-w-screen-xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">Daftar Barang</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <?php foreach($barang as $item): ?>
        <div class="bg-white rounded-lg shadow p-4">
            <img src="https://via.placeholder.com/300x200" class="rounded mb-3">

            <h3 class="text-lg font-bold"><?= $item['nama'] ?></h3>
            <p class="text-gray-600"><?= $item['harga'] ?></p>

            <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Sewa Sekarang
            </button>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>