<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Category;
use App\Models\Item;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $rentals    = Rental::with('customer', 'details.item')->latest()->get();
        $categories = Category::all();
        $items      = Item::with('category')->latest()->get();

        // Helper to check if category is camera-related
        $isCamera = fn($item) => ($item->category->name ?? '') === 'Camera';
        $isCamp = fn($item) => ($item->category->name ?? '') === 'Camping';

        // Stats for Dashboard Shortcut Sections
        $stats = [
            'manage_item' => [
                'listed_items' => [
                    'cameras' => $items->filter($isCamera)->sum('stock'),
                    'camps' => $items->filter($isCamp)->sum('stock'),
                ],
                'listed_series' => [
                    'cameras' => $items->filter($isCamera)->count(),
                    'camps' => $items->filter($isCamp)->count(),
                ],
                'rent_period' => [
                    'cameras' => Rental::where('status', 'active')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camera')->count(),
                    'camps' => Rental::where('status', 'active')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camping')->count(),
                ],
            ],
            'manage_category' => [
                'listed_category' => [
                    'cameras' => $categories->filter(fn($c) => $c->name === 'Camera')->count(),
                    'camps' => $categories->filter(fn($c) => $c->name === 'Camping')->count(),
                ],
            ],
            'manage_rent' => [
                'waiting_payment' => [
                    'cameras' => Rental::where('status', 'pending')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camera')->count(),
                    'camps' => Rental::where('status', 'pending')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camping')->count(),
                ],
                'rent_period' => [
                    'cameras' => Rental::where('status', 'active')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camera')->count(),
                    'camps' => Rental::where('status', 'active')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camping')->count(),
                ],
                'rent_finished' => [
                    'cameras' => Rental::where('status', 'completed')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camera')->count(),
                    'camps' => Rental::where('status', 'completed')->get()->flatMap->details->filter(fn($d) => ($d->item->category->name ?? '') === 'Camping')->count(),
                ],
            ]
        ];

        return view('admin.dashboard', compact('rentals', 'categories', 'items', 'stats'));
    }

    public function withdraw()
    {
        $paidRentals = Rental::where('payment_status', 'paid')->get();
        $totalIncome = $paidRentals->sum('total_amount');

        $now = Carbon::now();
        
        $summaries = [
            'today' => $paidRentals->filter(fn($r) => Carbon::parse($r->updated_at)->isToday())->sum('total_amount'),
            'this_week' => $paidRentals->filter(fn($r) => Carbon::parse($r->updated_at)->isSameWeek($now))->sum('total_amount'),
            'this_month' => $paidRentals->filter(fn($r) => Carbon::parse($r->updated_at)->isSameMonth($now))->sum('total_amount'),
            'this_year' => $paidRentals->filter(fn($r) => Carbon::parse($r->updated_at)->isSameYear($now))->sum('total_amount'),
            'history' => $paidRentals->groupBy(fn($r) => Carbon::parse($r->updated_at)->format('Y-m-d'))
                                     ->map(fn($group) => $group->sum('total_amount'))
                                     ->sortKeysDesc()
                                     ->take(10)
        ];

        return view('admin.withdraw', compact('totalIncome', 'summaries'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', '✅ Profil admin berhasil diperbarui');
    }

    public function approve(Request $request)
    {
        $request->validate(['rental_id' => 'required|exists:rentals,id']);
        Rental::where('id', $request->rental_id)->where('status', 'pending')
              ->update(['status' => 'active']);
        return back()->with('success', '✅ Sewa disetujui');
    }

    public function returnItem(Request $request)
    {
        $request->validate(['rental_id' => 'required|exists:rentals,id']);
        
        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $rental = Rental::findOrFail($request->rental_id);
            
            $retDate = Carbon::parse($request->return_date ?? now());
            
            // Late fee is based on the total daily rate of all items in the rental
            $totalDailyRate = $rental->details->sum(fn($d) => $d->item->daily_rate);
            
            $lateFee = $this->calculateLateFee($rental->end_date, $retDate, $totalDailyRate);

            $rental->update([
                'status'             => 'completed',
                'actual_return_date' => $retDate,
                'late_fee'           => $lateFee,
                'total_amount'       => $rental->total_amount + $lateFee,
                'payment_status'     => 'paid'
            ]);
            
            // Restore item stock
            foreach($rental->details as $detail) {
                $detail->item->increment('stock');
            }
        });

        return back()->with('success', "📦 Dikembalikan. Denda: Rp" . number_format($request->late_fee ?? 0, 0, ',', '.'));
    }

    public function addCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create(['name' => $catName = $request->name]);
        return back()->with('success', "✅ Kategori '$catName' ditambahkan");
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);
        $category = Category::findOrFail($request->id);
        $oldName = $category->name;
        $category->update(['name' => $request->name]);
        return back()->with('success', "✅ Kategori '$oldName' diubah menjadi '$request->name'");
    }

    public function deleteCategory(Request $request)
    {
        $request->validate(['id' => 'required|exists:categories,id']);
        $category = Category::findOrFail($request->id);
        
        if ($category->items()->count() > 0) {
            return back()->with('error', "❌ Tidak dapat menghapus kategori '$category->name' karena masih memiliki barang.");
        }

        $name = $category->name;
        $category->delete();
        return back()->with('success', "✅ Kategori '$name' berhasil dihapus");
    }

    public function bulkDeleteCategories(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:categories,id']);
        
        $categories = Category::whereIn('id', $request->ids)->get();
        $deletedCount = 0;
        $failedCount = 0;

        foreach ($categories as $category) {
            if ($category->items()->count() === 0) {
                $category->delete();
                $deletedCount++;
            } else {
                $failedCount++;
            }
        }

        $msg = "✅ $deletedCount kategori berhasil dihapus.";
        if ($failedCount > 0) {
            $msg .= " ❌ $failedCount kategori gagal dihapus karena masih memiliki barang.";
        }

        return back()->with($failedCount > 0 ? 'error' : 'success', $msg);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'daily_rate'  => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imageUrl = 'https://placehold.co/800x600/e2e8f0/64748b?text=CampLens+Gear';
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $imageUrl = Storage::url($path);
        }

        Item::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'daily_rate'  => $request->daily_rate,
            'stock'       => $request->stock,
            'image_url'   => $imageUrl
        ]);

        return back()->with('success', "✅ Barang '$request->name' berhasil ditambahkan");
    }

    public function updateItem(Request $request)
    {
        $request->validate([
            'id'          => 'required|exists:items,id',
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'daily_rate'  => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $item = Item::findOrFail($request->id);
        
        $data = [
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'daily_rate'  => $request->daily_rate,
            'stock'       => $request->stock,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if it exists and is not a placeholder
            if ($item->image_url && strpos($item->image_url, '/storage/') !== false) {
                $oldPath = str_replace('/storage/', '', $item->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('items', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $item->update($data);

        return back()->with('success', "✅ Barang '$request->name' berhasil diperbarui");
    }

    public function deleteItem(Request $request)
    {
        $request->validate(['id' => 'required|exists:items,id']);
        $item = Item::findOrFail($request->id);

        // Check if item has active rentals
        $hasActiveRentals = $item->rentalDetails()->whereHas('rental', function($q) {
            $q->whereIn('status', ['pending', 'active']);
        })->exists();

        if ($hasActiveRentals) {
            return back()->with('error', "❌ Tidak dapat menghapus barang '$item->name' karena sedang dalam proses penyewaan.");
        }

        // Delete image if not placeholder
        if ($item->image_url && strpos($item->image_url, '/storage/') !== false) {
            $oldPath = str_replace('/storage/', '', $item->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $name = $item->name;
        $item->delete();

        return back()->with('success', "✅ Barang '$name' berhasil dihapus");
    }

    public function bulkDeleteItems(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:items,id']);
        
        $items = Item::whereIn('id', $request->ids)->get();
        $deletedCount = 0;
        $failedCount = 0;

        foreach ($items as $item) {
            $hasActiveRentals = $item->rentalDetails()->whereHas('rental', function($q) {
                $q->whereIn('status', ['pending', 'active']);
            })->exists();

            if (!$hasActiveRentals) {
                if ($item->image_url && strpos($item->image_url, '/storage/') !== false) {
                    $oldPath = str_replace('/storage/', '', $item->image_url);
                    Storage::disk('public')->delete($oldPath);
                }
                $item->delete();
                $deletedCount++;
            } else {
                $failedCount++;
            }
        }

        $msg = "✅ $deletedCount barang berhasil dihapus.";
        if ($failedCount > 0) {
            $msg .= " ❌ $failedCount barang gagal dihapus karena sedang dalam penyewaan.";
        }

        return back()->with($failedCount > 0 ? 'error' : 'success', $msg);
    }

    public function items()
    {
        $categories = Category::all();
        $items      = Item::with('category')->latest()->get();
        return view('admin.items', compact('categories', 'items'));
    }

    public function categories()
    {
        $categories = Category::withCount('items')->get();
        return view('admin.categories', compact('categories'));
    }

    public function rentals()
    {
        $rentals = Rental::with('customer', 'details.item')->latest()->get();
        return view('admin.rentals', compact('rentals'));
    }

    private function calculateLateFee($endDate, $returnDate, $dailyRate, $graceHours = 2)
    {
        $end = Carbon::parse($endDate)->addHours($graceHours);
        if ($returnDate->lte($end)) return 0;
        
        $lateDays = ceil($returnDate->diffInHours($end) / 24);
        // Penalty: 50% of total daily rate per late day
        return $lateDays * ($dailyRate * 0.5);
    }
}