<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search nama item
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter harga max
        if ($request->filled('max_price')) {
            $query->where('daily_rate', '<=', $request->max_price);
        }

        // Filter Chips
        if ($request->filled('filter')) {
            match ($request->filter) {
                'In Stock'     => $query->where('stock', '>', 0),
                'Under 100k'   => $query->where('daily_rate', '<=', 100000),
                'Premium Gear' => $query->where('daily_rate', '>=', 150000),
                default        => null
            };
        }

        $items = $query->latest()->get();
        $cats  = Category::all();

        return view('items.index', compact('items', 'cats'));
    }

    // ✅ pindahkan ke dalam class
    public function show(Item $item)
    {
        $item->load('category');

        // Ambil produk serupa
        $relatedItems = Item::where('category_id', $item->category_id)
                            ->where('id', '!=', $item->id)
                            ->take(4)
                            ->get();

        return view('items.show', compact('item', 'relatedItems'));
    }
}