<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $cats = Category::all();
        $items = Item::with('category')->latest()->get();
        
        return view('home', compact('cats', 'items'));
    }
}