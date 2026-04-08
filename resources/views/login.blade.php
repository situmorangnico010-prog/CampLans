<?php
$message = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Dummy login
    if($email == "admin@gmail.com" && $password == "123"){
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Email atau password salah";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-center">Login Rental</h2>

    <?php if($message): ?>
        <div class="mb-4 text-sm text-red-500"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded-lg p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded-lg p-2" required>
        </div>

        <button type="submit" name="login" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
            Login
        </button>
    </form>

    <p class="text-sm mt-4 text-center">
        Belum punya akun? <a href="register.php" class="text-blue-600">Daftar</a>
    </p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>