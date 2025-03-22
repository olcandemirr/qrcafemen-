<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $tables = \App\Models\Table::all();
    return Inertia::render('Customer/Welcome', [
        'tables' => $tables,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        $stats = [
            'tables_count' => \App\Models\Table::count(),
            'products_count' => \App\Models\Product::count(),
            'categories_count' => \App\Models\Category::count(),
            'orders_count' => \App\Models\Order::count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    })->name('dashboard');

    Route::resource('tables', \App\Http\Controllers\Admin\TableController::class);
    Route::get('tables/{table}/qr', [\App\Http\Controllers\Admin\TableController::class, 'generateQr'])->name('tables.qr');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('allergens', \App\Http\Controllers\Admin\AllergenController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
});

// Customer Routes
Route::prefix('t/{table_id}')->name('customer.')->group(function () {
    // Menu Routes
    Route::get('/', [App\Http\Controllers\Customer\MenuController::class, 'index'])->name('menu');
    Route::get('/category/{category_id}', [App\Http\Controllers\Customer\MenuController::class, 'productsByCategory'])->name('category.products');
    Route::get('/product/{product_id}', [App\Http\Controllers\Customer\MenuController::class, 'showProduct'])->name('product.show');
    
    // Cart Routes
    Route::get('/cart', [App\Http\Controllers\Customer\OrderController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [App\Http\Controllers\Customer\OrderController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update', [App\Http\Controllers\Customer\OrderController::class, 'updateCartItem'])->name('cart.update');
    Route::delete('/cart/{index}', [App\Http\Controllers\Customer\OrderController::class, 'removeCartItem'])->name('cart.remove');
    
    // Order Routes
    Route::post('/order', [App\Http\Controllers\Customer\OrderController::class, 'placeOrder'])->name('order.store');
    Route::get('/order/{order_id}', [App\Http\Controllers\Customer\OrderController::class, 'orderStatus'])->name('order.status');
    Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'orderHistory'])->name('order.history');
    
    // Rating Routes
    Route::get('/order/{order_id}/product/{product_id}/rate', [App\Http\Controllers\Customer\ScoreController::class, 'create'])->name('product.rate');
    Route::post('/order/{order_id}/product/{product_id}/rate', [App\Http\Controllers\Customer\ScoreController::class, 'store'])->name('product.rate.store');
});

require __DIR__.'/auth.php';
