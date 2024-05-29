<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/edit/{id}', [DashboardController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [DashboardController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [DashboardController::class, 'delete'])->name('delete');
    Route::post('/createrols', [DashboardController::class, 'addrols'])->name('createrols');

    // Data Role
    Route::get('/datarols', [RolsController::class, 'index'])->name('rols');
    Route::post('/addrols', [RolsController::class, 'create'])->name('addrols');
    Route::get('/editrols/{id}', [RolsController::class, 'edit'])->name('editrols');
    Route::post('/updaterols/{id}', [RolsController::class, 'update'])->name('updaterols');
    Route::delete('/deleterols/{id}', [RolsController::class, 'delete'])->name('deleterols');
});

require __DIR__ . '/auth.php';
