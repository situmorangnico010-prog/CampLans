<?php

if(isset($_POST['register'])){
    // Dummy register (tidak disimpan)
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-center">Register</h2>

    <form method="POST">
        <div class="mb-4">
            <label class="block mb-1">Nama</label>
            <input type="text" name="nama" class="w-full border rounded-lg p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded-lg p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded-lg p-2" required>
        </div>

        <button type="submit" name="register" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
            Register
        </button>
    </form>

    <p class="text-sm mt-4 text-center">
        Sudah punya akun? <a href="login.php" class="text-blue-600">Login</a>
    </p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>