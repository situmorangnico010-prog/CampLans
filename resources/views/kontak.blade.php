<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak</title>

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <!-- Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-8">
            
            <!-- Title -->
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                Hubungi Kami
            </h2>

            <!-- Form -->
            <form action="#" method="POST" class="space-y-6">

                <!-- Nama -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Nama
                    </label>
                    <input type="text" name="nama"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Masukkan nama anda" required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input type="email" name="email"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Masukkan email anda" required>
                </div>

                <!-- Pesan -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Pesan
                    </label>
                    <textarea name="pesan" rows="4"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Tulis pesan anda..." required></textarea>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 transition">
                    Kirim Pesan
                </button>

            </form>

        </div>
    </div>

    @vite('resources/js/app.js')
</body>
</html>