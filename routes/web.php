<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ItemController,
    CartController,
    RentalController,
    AdminController,
    ProfileController,
    AccountController
};

// 🌐 Public Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// 📄 Detail Item (Public)
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// 🔐 Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // 📊 Dashboard Redirect (role-based)
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('items.index');
    })->name('dashboard');

    // 🛒 Customer Routes
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::post('/rentals/pay', [RentalController::class, 'pay'])->name('rentals.pay');
    Route::post('/rentals/extend', [RentalController::class, 'extend'])->name('rentals.extend');

    // 👤 Account / Profile
    Route::get('/account', [AccountController::class, 'index'])->name('account.edit');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');

    // ❓ Help Page
    Route::get('/help', function () {
        return view('help');
    })->name('help');

    // 👑 Admin Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.updateSettings');
        Route::get('/admin/withdraw', [AdminController::class, 'withdraw'])->name('admin.withdraw');
        Route::get('/admin/items', [AdminController::class, 'items'])->name('admin.items');
        Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
        Route::get('/admin/rentals', [AdminController::class, 'rentals'])->name('admin.rentals');
        
        Route::post('/admin/approve', [AdminController::class, 'approve'])->name('admin.approve');
        Route::post('/admin/return', [AdminController::class, 'returnItem'])->name('admin.return');
        Route::post('/admin/add-category', [AdminController::class, 'addCategory'])->name('admin.addCategory');
        Route::post('/admin/update-category', [AdminController::class, 'updateCategory'])->name('admin.updateCategory');
        Route::post('/admin/delete-category', [AdminController::class, 'deleteCategory'])->name('admin.deleteCategory');
        Route::post('/admin/add-item', [AdminController::class, 'addItem'])->name('admin.addItem');
        Route::post('/admin/update-item', [AdminController::class, 'updateItem'])->name('admin.updateItem');
        Route::post('/admin/delete-item', [AdminController::class, 'deleteItem'])->name('admin.deleteItem');
        Route::post('/admin/bulk-delete-items', [AdminController::class, 'bulkDeleteItems'])->name('admin.bulkDeleteItems');
        Route::post('/admin/bulk-delete-categories', [AdminController::class, 'bulkDeleteCategories'])->name('admin.bulkDeleteCategories');
    });

});

// 🔑 Breeze Auth Routes (WAJIB ADA)
require __DIR__.'/auth.php';