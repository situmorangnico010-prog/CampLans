<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@camplans.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $categories = ['Camera', 'Camping'];
        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }

        $items = [
            [
                'category_id' => 1,
                'name' => 'Canon EOS R50',
                'daily_rate' => 150000,
                'stock' => 2,
                'description' => 'Kamera mirrorless ringkas dan ringan, sangat cocok untuk pembuat konten pemula.',
            ],
            [
                'category_id' => 1,
                'name' => 'Sony Alpha a6400',
                'daily_rate' => 140000,
                'stock' => 2,
                'description' => 'Kamera APS-C mirrorless dengan fokus otomatis super cepat.',
            ],
            [
                'category_id' => 2,
                'name' => 'Tenda Camping 4P',
                'daily_rate' => 50000,
                'stock' => 3,
                'description' => 'Tenda double layer kapasitas 4 orang dengan rangka fiber yang kokoh.',
            ],
            [
                'category_id' => 2,
                'name' => 'Sleeping Bag',
                'daily_rate' => 30000,
                'stock' => 3,
                'description' => 'Sleeping bag tebal dengan lapisan polar hangat di bagian dalam.',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
