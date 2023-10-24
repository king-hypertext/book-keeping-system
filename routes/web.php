<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AdminController;

Route::get('/admin/auth/login', [ViewController::class, 'login'])->name('login');
Route::post('/admin/auth/login', [AdminController::class, 'verify_login']);
Route::get('/admin/auth/save', [ViewController::class, 'signup'])->name('save');
Route::post('/admin/auth/save', [AdminController::class, 'save']);
Route::get('/', function () {
    return redirect('/admin/dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('home');
    Route::get('/customers/search', [AdminController::class, 'searchCustomer']);
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/admin/add-customer', [AdminController::class, 'create'])->name('add-customer');
    Route::post('/add-customer', [AdminController::class, 'addCustomer']);
    Route::get('/customer/{customer}/', [AdminController::class, 'showCustomer']);
    Route::post('/customer/{customer}/edit', [AdminController::class, 'editCustomer']);
    Route::post('/customer/{customer}/deposit', [AdminController::class, 'deposit']);
    Route::post('/customer/{customer}/withdraw', [AdminController::class, 'withdraw']);
    Route::post('/customer/{customer}/delete', [AdminController::class, 'deleteCustomer']);
    Route::post('/admin/{admin}/destroy', [AdminController::class, 'deleteUser']);
    Route::post('/admin/{admin}/update', [AdminController::class, 'update']);
    Route::post('/admin/{admin}/upload', [AdminController::class, 'upload']);
    Route::post('/admin/auth/logout', [AdminController::class, 'logout'])->name('logout');
});
