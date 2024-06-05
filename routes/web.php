<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduckController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RolsController;
use App\Http\Controllers\UserController;
use App\Models\Rols;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Data User
    Route::get('/create', [DashboardController::class, 'create'])->name('create');
    Route::post('/store', [DashboardController::class, 'store'])->name('store');
    // Route::get('/edit/{id}', [DashboardController::class, 'edit'])->name('edit');
    Route::get('/edit/{id}', [DashboardController::class, 'getUserData']);
    Route::post('/update', [DashboardController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [DashboardController::class, 'delete'])->name('delete');
    Route::post('/createrole', [DashboardController::class, 'createroles'])->name('createrolesuser');

    // Data Role
    Route::get('/datarole', [RolesController::class, 'index'])->name('role');
    Route::post('/createroles', [RolesController::class, 'create'])->name('createroles');
    Route::get('/editroles/{id}', [RolesController::class, 'edit'])->name('editroles');
    Route::post('/updateroles/{id}', [RolesController::class, 'update'])->name('updatedroles');
    Route::delete('/deleteroles/{id}', [RolesController::class, 'delete'])->name('deleteroles');

    // Data Product
    Route::get('/products', [ProductController::class, 'index'])->name('dataproducts');
    Route::post('/createproducts', [ProductController::class, 'store'])->name('createproducts');
    Route::get('/editproduct/{id}', [ProductController::class, 'edit'])->name('editproduct');
    Route::post('/updatedproduct/{id}', [ProductController::class, 'update'])->name('updatedproduct');
    Route::delete('/deleteproducts/{id}', [ProductController::class, 'delete'])->name('deleteproducts');

    // Data Category
    Route::get('/categorys', [CategoryController::class, 'index'])->name('datacategory');
    Route::post('/createcategory', [CategoryController::class, 'create'])->name('createcategory');
});

require __DIR__ . '/auth.php';
