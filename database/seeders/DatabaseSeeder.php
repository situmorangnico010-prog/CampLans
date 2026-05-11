<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@camplans.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Categories
        $categories = ['Camera', 'Camping'];
        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }

        // Items
        $items = [
            ['category_id' => 1, 'name' => 'Canon EOS R50', 'daily_rate' => 150000, 'stock' => 2],
            ['category_id' => 1, 'name' => 'Sony Alpha a6400', 'daily_rate' => 140000, 'stock' => 2],
            ['category_id' => 1, 'name' => 'Canon EOS 5D Mark IV', 'daily_rate' => 200000, 'stock' => 1],
            ['category_id' => 1, 'name' => 'Fujifilm X-T5', 'daily_rate' => 160000, 'stock' => 2],
            ['category_id' => 2, 'name' => 'Tenda Camping 4P', 'daily_rate' => 50000, 'stock' => 3],
            ['category_id' => 2, 'name' => 'Sleeping Bag', 'daily_rate' => 30000, 'stock' => 3],
            ['category_id' => 1, 'name' => 'Canon 50mm f/1.8', 'daily_rate' => 80000, 'stock' => 2],
            ['category_id' => 1, 'name' => 'Sony 85mm f/1.4', 'daily_rate' => 120000, 'stock' => 1],
            ['category_id' => 1, 'name' => 'Tripod Manfrotto', 'daily_rate' => 40000, 'stock' => 2],
            ['category_id' => 1, 'name' => 'Camera Bag', 'daily_rate' => 35000, 'stock' => 3],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}