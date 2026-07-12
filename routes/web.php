<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    CategoryController as AdminCategoryController,
    ItemController as AdminItemController,
    RentalController as AdminRentalController,
    SettingsController as AdminSettingsController
};
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/app', function () {
    return view('app');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/product', function () {
    return view('product');
});

Route::resource('products', ProductController::class);

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add/{id}', [CartController::class, 'add']);
Route::get('/cart/remove/{id}', [CartController::class, 'remove']);

Route::middleware(['auth'])->group(function () {

    // ─── 👑 Admin Routes ─────────────────────────────────────────────
    Route::middleware(['admin'])->group(function () {

        Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/admin/items', [AdminItemController::class, 'items'])->name('admin.items');
        Route::get('/admin/categories', [AdminCategoryController::class, 'categories'])->name('admin.categories');

        Route::get('/admin/rentals', [AdminRentalController::class, 'rentals'])->name('admin.rentals');
        Route::post('/admin/rentals/update-status', [AdminRentalController::class, 'updateRentalStatus'])->name('admin.updateStatus');
        Route::post('/admin/rentals/cancel', [AdminRentalController::class, 'cancelRental'])->name('admin.cancelRental');

        Route::get('/admin/payments', [AdminRentalController::class, 'payments'])->name('admin.payments');
        Route::get('/admin/payments/{rental}', [AdminRentalController::class, 'paymentDetail'])->name('admin.paymentDetail');
        Route::post('/admin/payments/verify', [AdminRentalController::class, 'verifyPayment'])->name('admin.verifyPayment');

        Route::get('/admin/settings/payment', [AdminSettingsController::class, 'paymentSettings'])->name('admin.paymentSettings');
        Route::post('/admin/settings/payment', [AdminSettingsController::class, 'savePaymentSettings'])->name('admin.savePaymentSettings');

        Route::get('/admin/withdraw', [AdminRentalController::class, 'withdraw'])->name('admin.withdraw');

        Route::get('/admin/settings', [AdminSettingsController::class, 'settings'])->name('admin.settings');
        Route::post('/admin/settings', [AdminSettingsController::class, 'updateSettings'])->name('admin.updateSettings');

        Route::post('/admin/add-category', [AdminCategoryController::class, 'addCategory'])->name('admin.addCategory');
        Route::post('/admin/update-category', [AdminCategoryController::class, 'updateCategory'])->name('admin.updateCategory');
        Route::post('/admin/delete-category', [AdminCategoryController::class, 'deleteCategory'])->name('admin.deleteCategory');
        Route::post('/admin/bulk-delete-categories', [AdminCategoryController::class, 'bulkDeleteCategories'])->name('admin.bulkDeleteCategories');

        Route::post('/admin/add-item', [AdminItemController::class, 'addItem'])->name('admin.addItem');
        Route::post('/admin/update-item', [AdminItemController::class, 'updateItem'])->name('admin.updateItem');
        Route::post('/admin/delete-item', [AdminItemController::class, 'deleteItem'])->name('admin.deleteItem');
        Route::post('/admin/bulk-delete-items', [AdminItemController::class, 'bulkDeleteItems'])->name('admin.bulkDeleteItems');
    });

});

require __DIR__.'/auth.php';
