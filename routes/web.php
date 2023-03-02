<?php

// Backend
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\VariationController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ADMIN:: Auth
Route::controller(AuthController::class)
    ->name('admin.auth.')
    ->prefix('cms-admin')
    ->group(function () {
        // Register
        Route::get('register', 'register')->name('register');
        Route::post('store-register', 'storeRegister')->name('register.store');

        // Login
        Route::get('login', 'login')->name('login');
        Route::post('store-login', 'storeLogin')->name('login.store');

        // Logout
        Route::post('logout', 'logout')->name('logout');
    });

// Backend
Route::prefix('cms-admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
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

            // Get Data With Category
            Route::post('get-brand-with-category', 'getBrandWithCategory')->name('getBrandWithCategory');

            // Exists
            Route::post('exist-data', 'checkExistData')->name('checkExistData');
        });

    // Slider
    Route::controller(SliderController::class)
        ->name('slider.')
        ->prefix('slider')
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

    // Attribute
    Route::controller(AttributeController::class)
        ->name('attribute.')
        ->prefix('attribute')
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

    // Variation
    Route::controller(VariationController::class)
        ->name('variation.')
        ->prefix('variation')
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

    // Product
    Route::controller(ProductController::class)
        ->name('product.')
        ->prefix('product')
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
