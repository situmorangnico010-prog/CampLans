<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

/**
 * Controller untuk mengelola keranjang belanja (Cart) pelanggan.
 * Menyimpan data keranjang di dalam session.
 */
class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja beserta item yang ada di dalamnya.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    /**
     * Menambahkan item (produk) ke dalam keranjang belanja session.
     * Jika produk sudah ada, maka kuantitasnya akan ditambahkan.
     */
    public function add($id)
    {
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "qty" => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Produk ditambahkan ke keranjang');
    }

    /**
     * Menghapus item (produk) dari keranjang belanja session berdasarkan ID.
     */
    public function remove($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect('/cart');
    }
}