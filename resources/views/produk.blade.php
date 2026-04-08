<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko sewa Kamera dan Alat Camping</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Toko sewa Kamera dan Alat Camping</h1>
            <div class="flex items-center space-x-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-phone mr-2"></i>
                    <span>(555)412-1234</span>
                </div>
                <button class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-600 transition-colors">
                    Login
                </button>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-gray-800 text-white">
        <div class="container mx-auto px-4">
            <ul class="flex space-x-8 py-3">
                <li><a href="#" class="hover:text-blue-300 transition-colors">Home</a></li>
                <li><a href="#" class="hover:text-blue-300 transition-colors">Company</a></li>
                <li><a href="#" class="hover:text-blue-300 transition-colors">Team</a></li>
                <li><a href="#" class="hover:text-blue-300 transition-colors">Features</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Produk Tersedia untuk Disewa</h2>
            <p class="text-gray-600">Pilih dari berbagai kamera dan alat camping berkualitas kami</p>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                <li class="mr-2">
                    <button class="inline-block p-4 rounded-t-lg border-b-2 border-primary text-primary" id="all-tab">Semua</button>
                </li>
                <li class="mr-2">
                    <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" id="camera-tab">Kamera</button>
                </li>
                <li class="mr-2">
                    <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" id="camping-tab">Alat Camping</button>
                </li>
            </ul>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Camera Products -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card camera-item">
                <img src="https://picsum.photos/seed/camera1/400/250.jpg" alt="Canon EOS R5" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Canon EOS R5</h3>
                    <p class="text-gray-600 text-sm mb-3">Kamera mirrorless full-frame 45MP</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">Rp 500.000/hari</span>
                        <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cart-plus mr-1"></i> Sewa
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card camera-item">
                <img src="https://picsum.photos/seed/camera2/400/250.jpg" alt="Sony A7 IV" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Sony A7 IV</h3>
                    <p class="text-gray-600 text-sm mb-3">Kamera mirrorless full-frame 33MP</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">Rp 450.000/hari</span>
                        <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cart-plus mr-1"></i> Sewa
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card camera-item">
                <img src="https://picsum.photos/seed/camera3/400/250.jpg" alt="DJI Ronin S" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">DJI Ronin S</h3>
                    <p class="text-gray-600 text-sm mb-3">Gimbal stabilizer untuk kamera</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">Rp 250.000/hari</span>
                        <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cart-plus mr-1"></i> Sewa
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card camera-item">
                <img src="https://picsum.photos/seed/camera4/400/250.jpg" alt="GoPro Hero 11" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">GoPro Hero 11</h3>
                    <p class="text-gray-600 text-sm mb-3">Action camera 5.3K 60fps</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">Rp 150.000/hari</span>
                        <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cart-plus mr-1"></i> Sewa
                        </button>
                    </div>
                </div>
            </div>

            <!-- Camping Products -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card camping-item">
                <img src="https://picsum.photos/seed/tent1/400/250.jpg" alt="Tenda Dome 4 Orang" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Tenda Dome 4 Orang</h3>
                    <p class="text-gray-600 text-sm mb-3">Tenda waterproof kapasitas 4 orang</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">Rp 200.000/hari</span>
                        <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cart-plus mr-1"></i> Sewa
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card camping-item">
                <img src="https://picsum.photos/seed/sleepingbag/400/250.jpg" alt="Sleeping Bag" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Sleeping Bag</h3>
                    <p class="text-gray-600 text-sm mb-3">Kantong tidur waterproof suhu rendah</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">Rp 75.000/hari</span>
                        <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cart-plus mr-1"></i> Sewa
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card camping-item">
                <img src="https://picsum.photos/seed/backpack/400/250.jpg" alt="Carrier 60L" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Carrier 60L</h3>
                    <p class="text-gray-600 text-sm mb-3">Tas carrier kapasitas 60 liter</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">Rp 100.000/hari</span>
                        <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cart-plus mr-1"></i> Sewa
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card camping-item">
                <img src="https://picsum.photos/seed/stove/400/250.jpg" alt="Kompor Camping" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Kompor Camping</h3>
                    <p class="text-gray-600 text-sm mb-3">Kompor portable dengan tabung gas</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">Rp 50.000/hari</span>
                        <button class="bg-primary text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cart-plus mr-1"></i> Sewa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-8">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <i class="fas fa-temperature-half mr-2"></i>
                    <span>26°</span>
                </div>
                <button class="flex items-center hover:bg-gray-700 px-3 py-1 rounded">
                    <i class="fas fa-bars mr-2"></i>
                    <span>Start</span>
                </button>
                <div class="relative">
                    <input type="text" placeholder="Search..." class="bg-gray-700 text-white px-3 py-1 rounded pr-8 w-64 focus:outline-none focus:ring-1 focus:ring-blue-400">
                    <i class="fas fa-search absolute right-2 top-2 text-gray-400"></i>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <i class="fas fa-folder text-xl hover:text-blue-300 cursor-pointer"></i>
                <i class="fas fa-image text-xl hover:text-blue-300 cursor-pointer"></i>
                <i class="fas fa-music text-xl hover:text-blue-300 cursor-pointer"></i>
                <i class="fas fa-video text-xl hover:text-blue-300 cursor-pointer"></i>
                <i class="fas fa-calculator text-xl hover:text-blue-300 cursor-pointer"></i>
                <i class="fas fa-cog text-xl hover:text-blue-300 cursor-pointer"></i>
            </div>
            
            <div class="flex items-center">
                <i class="fas fa-wifi mr-2"></i>
                <i class="fas fa-battery-three-quarters mr-3"></i>
                <span>20:18</span>
                <span class="ml-3">07/04/2026</span>
            </div>
        </div>
    </footer>

    <script>
        // Tab functionality
        document.getElementById('all-tab').addEventListener('click', function() {
            showAllProducts();
            setActiveTab('all-tab');
        });

        document.getElementById('camera-tab').addEventListener('click', function() {
            showProducts('camera');
            setActiveTab('camera-tab');
        });

        document.getElementById('camping-tab').addEventListener('click', function() {
            showProducts('camping');
            setActiveTab('camping-tab');
        });

        function showAllProducts() {
            document.querySelectorAll('.product-card').forEach(card => {
                card.style.display = 'block';
            });
        }

        function showProducts(category) {
            document.querySelectorAll('.product-card').forEach(card => {
                card.style.display = 'none';
            });
            
            if (category === 'camera') {
                document.querySelectorAll('.camera-item').forEach(card => {
                    card.style.display = 'block';
                });
            } else if (category === 'camping') {
                document.querySelectorAll('.camping-item').forEach(card => {
                    card.style.display = 'block';
                });
            }
        }

        function setActiveTab(tabId) {
            document.querySelectorAll('#all-tab, #camera-tab, #camping-tab').forEach(tab => {
                tab.classList.remove('border-primary', 'text-primary');
                tab.classList.add('border-transparent');
            });
            
            document.getElementById(tabId).classList.remove('border-transparent');
            document.getElementById(tabId).classList.add('border-primary', 'text-primary');
        }

        // Update time
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();
            
            document.querySelector('.fa-battery-three-quarters').nextElementSibling.textContent = `${hours}:${minutes}`;
            document.querySelector('.fa-battery-three-quarters').nextElementSibling.nextElementSibling.textContent = `${day}/${month}/${year}`;
        }
        
        updateTime();
        setInterval(updateTime, 60000); // Update every minute
    </script>
</body>
</html>