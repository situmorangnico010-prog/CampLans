<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk mengelola data barang/peralatan sewa.
 * Menangani penambahan, pembaruan, penghapusan, dan pengunggahan foto barang ke direktori storage/products.
 */
class ItemController extends Controller
{
    /**
     * Menampilkan daftar inventaris barang beserta kategorinya di panel admin.
     *
     * @return \Illuminate\View\View
     */
    public function items()
    {
        $categories = Category::all();
        $items      = Item::with('category')->latest()->get();
        return view('admin.items', compact('categories', 'items'));
    }

    /**
     * Menyimpan data barang baru beserta foto yang diunggah ke database.
     *
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addItem(StoreProductRequest $request)
    {
        $imagePath = null;
        $imageUrl  = 'https://placehold.co/800x600/e2e8f0/64748b?text=CampLens+Gear';

        // Mengupload foto barang ke folder storage/products menggunakan disk public
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $imageUrl  = Storage::disk('public')->url($imagePath);
        }

        Item::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'daily_rate'  => $request->daily_rate,
            'stock'       => $request->stock,
            'description' => $request->description,
            'image'       => $imagePath, // Menyimpan relative path ke DB (contoh: products/file.jpg)
            'image_url'   => $imageUrl,  // Menyimpan URL absolut untuk fallback kemudahan UI
        ]);

        return back()->with('success', "✅ Barang '{$request->name}' berhasil ditambahkan");
    }

    /**
     * Memperbarui data barang dan mengganti foto lama jika foto baru diunggah.
     *
     * @param UpdateProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateItem(UpdateProductRequest $request)
    {
        $item = Item::findOrFail($request->id);
        
        $data = [
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'daily_rate'  => $request->daily_rate,
            'stock'       => $request->stock,
            'description' => $request->description,
        ];

        // Jika terdapat unggahan file foto baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada di storage
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            } elseif ($item->image_url && strpos($item->image_url, '/storage/') !== false) {
                $oldPath = str_replace('/storage/', '', $item->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan foto baru ke public/products
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image']     = $imagePath;
            $data['image_url'] = Storage::disk('public')->url($imagePath);
        }

        $item->update($data);

        return back()->with('success', "✅ Barang '{$request->name}' berhasil diperbarui");
    }

    /**
     * Menghapus barang sewa jika tidak sedang disewa/dibooking oleh customer.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteItem(Request $request)
    {
        $request->validate(['id' => 'required|exists:items,id']);
        
        $item = Item::findOrFail($request->id);

        // Memeriksa apakah barang sedang aktif dalam penyewaan
        $hasActiveRentals = $item->rentalDetails()->whereHas('rental', function ($q) {
            $q->whereNotIn('transaction_status', ['cancelled', 'completed', 'expired']);
        })->exists();

        if ($hasActiveRentals) {
            return back()->with('error', "❌ Tidak dapat menghapus barang '{$item->name}' karena sedang dalam proses penyewaan.");
        }

        // Hapus file foto dari disk storage
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        } elseif ($item->image_url && strpos($item->image_url, '/storage/') !== false) {
            $oldPath = str_replace('/storage/', '', $item->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $name = $item->name;
        $item->delete();

        return back()->with('success', "✅ Barang '{$name}' berhasil dihapus");
    }

    /**
     * Menghapus banyak barang sekaligus yang dipilih jika tidak sedang aktif dalam penyewaan.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDeleteItems(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:items,id'
        ]);

        $items        = Item::whereIn('id', $request->ids)->get();
        $deletedCount = 0;
        $failedCount  = 0;

        foreach ($items as $item) {
            $hasActiveRentals = $item->rentalDetails()->whereHas('rental', function ($q) {
                $q->whereNotIn('transaction_status', ['cancelled', 'completed', 'expired']);
            })->exists();

            if (!$hasActiveRentals) {
                // Hapus file gambar
                if ($item->image && Storage::disk('public')->exists($item->image)) {
                    Storage::disk('public')->delete($item->image);
                } elseif ($item->image_url && strpos($item->image_url, '/storage/') !== false) {
                    $oldPath = str_replace('/storage/', '', $item->image_url);
                    Storage::disk('public')->delete($oldPath);
                }
                
                $item->delete();
                $deletedCount++;
            } else {
                $failedCount++;
            }
        }

        $msg = "✅ {$deletedCount} barang berhasil dihapus.";
        if ($failedCount > 0) {
            $msg .= " ❌ {$failedCount} barang gagal dihapus karena sedang dalam penyewaan.";
        }

        return back()->with($failedCount > 0 ? 'error' : 'success', $msg);
    }
}
