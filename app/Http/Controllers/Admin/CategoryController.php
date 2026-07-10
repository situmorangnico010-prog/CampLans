<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola kategori barang.
 * Bertanggung jawab terhadap pembuatan, pembaruan, dan penghapusan kategori.
 */
class CategoryController extends Controller
{
    /**
     * Menampilkan daftar seluruh kategori barang beserta jumlah barang di dalamnya.
     *
     * @return \Illuminate\View\View
     */
    public function categories()
    {
        $categories = Category::withCount('items')->get();
        return view('admin.categories', compact('categories'));
    }

    /**
     * Menambahkan kategori baru ke database.
     *
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCategory(StoreCategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name
        ]);

        return back()->with('success', "✅ Kategori '{$category->name}' berhasil ditambahkan");
    }

    /**
     * Memperbarui nama kategori yang dipilih.
     *
     * @param UpdateCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCategory(UpdateCategoryRequest $request)
    {
        $category = Category::findOrFail($request->id);
        $oldName  = $category->name;
        $category->update([
            'name' => $request->name
        ]);

        return back()->with('success', "✅ Kategori '{$oldName}' diubah menjadi '{$request->name}'");
    }

    /**
     * Menghapus satu kategori tertentu jika tidak memiliki relasi barang aktif.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCategory(Request $request)
    {
        $request->validate(['id' => 'required|exists:categories,id']);
        
        $category = Category::findOrFail($request->id);

        if ($category->items()->count() > 0) {
            return back()->with('error', "❌ Tidak dapat menghapus kategori '{$category->name}' karena masih memiliki barang.");
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', "✅ Kategori '{$name}' berhasil dihapus");
    }

    /**
     * Menghapus banyak kategori sekaligus yang terpilih jika tidak memiliki relasi barang aktif.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDeleteCategories(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        $categories   = Category::whereIn('id', $request->ids)->get();
        $deletedCount = 0;
        $failedCount  = 0;

        foreach ($categories as $category) {
            if ($category->items()->count() === 0) {
                $category->delete();
                $deletedCount++;
            } else {
                $failedCount++;
            }
        }

        $msg = "✅ {$deletedCount} kategori berhasil dihapus.";
        if ($failedCount > 0) {
            $msg .= " ❌ {$failedCount} kategori gagal dihapus karena masih memiliki barang.";
        }

        return back()->with($failedCount > 0 ? 'error' : 'success', $msg);
    }
}
