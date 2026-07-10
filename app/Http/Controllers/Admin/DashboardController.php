<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Category;
use App\Models\Item;

/**
 * Controller untuk mengelola dashboard admin.
 * Bertanggung jawab menampilkan ringkasan data, statistik, dan metrik bisnis CampLens.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama admin beserta metrik statistiknya.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil seluruh data rental beserta customer dan detail barang pendukung
        $rentals    = Rental::with('customer', 'details.item')->latest()->get();
        $categories = Category::all();
        $items      = Item::with('category')->latest()->get();

        // Helper closures untuk penyaringan kategori
        $isCamera = fn($item) => ($item->category->name ?? '') === 'Camera';
        $isCamp   = fn($item) => ($item->category->name ?? '') === 'Camping';

        // Penyusunan data statistik berdasarkan kategori
        $stats = [
            'manage_item' => [
                'listed_items' => [
                    'cameras' => $items->filter($isCamera)->sum('stock'),
                    'camps'   => $items->filter($isCamp)->sum('stock'),
                ],
                'listed_series' => [
                    'cameras' => $items->filter($isCamera)->count(),
                    'camps'   => $items->filter($isCamp)->count(),
                ],
                'rent_period' => [
                    'cameras' => Rental::where('transaction_status', 'on_rent')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camera')->count(),
                    'camps'   => Rental::where('transaction_status', 'on_rent')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camping')->count(),
                ],
            ],
            'manage_category' => [
                'listed_category' => [
                    'cameras' => $categories->filter(fn($c) => $c->name === 'Camera')->count(),
                    'camps'   => $categories->filter(fn($c) => $c->name === 'Camping')->count(),
                ],
            ],
            'manage_rent' => [
                'waiting_payment' => [
                    'cameras' => Rental::where('transaction_status', 'waiting_verification')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camera')->count(),
                    'camps'   => Rental::where('transaction_status', 'waiting_verification')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camping')->count(),
                ],
                'rent_period' => [
                    'cameras' => Rental::where('transaction_status', 'on_rent')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camera')->count(),
                    'camps'   => Rental::where('transaction_status', 'on_rent')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camping')->count(),
                ],
                'rent_finished' => [
                    'cameras' => Rental::where('transaction_status', 'completed')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camera')->count(),
                    'camps'   => Rental::where('transaction_status', 'completed')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camping')->count(),
                ],
            ],
        ];

        // Ringkasan status untuk quick view dashboard
        $quickStats = [
            'waiting_verification' => Rental::where('transaction_status', 'waiting_verification')->count(),
            'on_rent'              => Rental::where('transaction_status', 'on_rent')->count(),
            'total_income'         => Rental::where('payment_status', 'paid')->sum('total_amount'),
            'total_rentals'        => Rental::count(),
        ];

        return view('admin.dashboard', compact('rentals', 'categories', 'items', 'stats', 'quickStats'));
    }
}
