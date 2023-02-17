<?php

use Illuminate\Support\Facades\Route;

// Backend
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Backend
Route::prefix('cms-admin')->name('admin.')->group(function () {
    // Dashboard
    Route::controller(DashboardController::class)
        ->name('dashboard.')
        ->prefix('dashboard')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });

    // Category
    Route::controller(CategoryController::class)
        ->name('category.')
        ->prefix('category')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
});

//Route::name('admin.')->group(function () {
//    Route::get('/users', function () {
//        // Route assigned name "admin.users"...
//    })->name('users');
//});
