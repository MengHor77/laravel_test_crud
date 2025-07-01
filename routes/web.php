<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Main resource routes with show enabled
Route::resource('products', ProductController::class);

// Additional product management routes
Route::prefix('products')->name('products.')->group(function () {
    Route::get('export', [ProductController::class, 'export'])
         ->name('export')
         ->middleware('auth');
         
    Route::get('low-stock', [ProductController::class, 'lowStock'])
         ->name('low-stock')
         ->middleware('auth');
         
    Route::get('trashed', [ProductController::class, 'trashed'])
         ->name('trashed');
         
    Route::patch('{product}/restore', [ProductController::class, 'restore'])
         ->name('restore');
         
    Route::delete('{product}/force-delete', [ProductController::class, 'forceDelete'])
         ->name('force-delete');
});

// If you need API routes (recommend putting in routes/api.php)
if (config('app.env') === 'local') {
    Route::prefix('api/products')->group(function () {
        Route::get('/', [ProductController::class, 'apiIndex']);
        Route::get('/{product}', [ProductController::class, 'apiShow']);
    });
}