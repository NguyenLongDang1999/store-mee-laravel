<?php

// Backend
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\BrandController;
use Illuminate\Support\Facades\Route;

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
            // List
            Route::get('/', 'index')->name('index');
            Route::get('get-list', 'getList')->name('getList');

            // Recycle List
            Route::get('recycle', 'recycle')->name('recycle');

            // Create
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');

            // Edit
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');

            // Delete
            Route::post('delete/{id}', 'delete')->name('delete');

            // Restore
            Route::post('restore/{id}', 'restore')->name('restore');

            // Exists
            Route::post('exist-data', 'checkExistData')->name('checkExistData');
        });

    // Brand
    Route::controller(BrandController::class)
        ->name('brand.')
        ->prefix('brand')
        ->group(function () {
            // List
            Route::get('/', 'index')->name('index');
            Route::get('get-list', 'getList')->name('getList');

            // Recycle List
            Route::get('recycle', 'recycle')->name('recycle');

            // Create
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');

            // Edit
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');

            // Delete
            Route::post('delete/{id}', 'delete')->name('delete');

            // Restore
            Route::post('restore/{id}', 'restore')->name('restore');

            // Exists
            Route::post('exist-data', 'checkExistData')->name('checkExistData');
        });
});
