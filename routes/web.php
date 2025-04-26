<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PelangganController;
use App\Models\Kasir;
use App\Http\Controllers\KasirController;

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

Route::get('/', [LoginController::class ,'view'])->name('login');
Route::get('/login', [LoginController::class ,'login'])->name('validationlog');

Route::get('/register', [RegisterController::class ,'register'])->name('register');
Route::post('/regist', [RegisterController::class ,'store'])->name('validationreg');

Route::get('/dashboard', [DashboardController::class ,'dashboard'])->name('dashboard')->middleware('auth');

Route::get('/logout', [LoginController::class ,'logout'])->name('logout')->middleware('auth');

Route::get('/product', [ProductController::class ,'index'])->name('indexp')->middleware('auth');

// CREATE PRODUCT 
Route::get('/create', [ProductController::class ,'create'])->name('create.product')->middleware('auth');
Route::post('/productstore', [ProductController::class ,'store'])->name('prostore')->middleware('auth');

// EDITPRODUCT
Route::get('/edit/{id}', [ProductController::class ,'edit'])->name('edit.product')->middleware('auth');
Route::put('/product/update/{product}', [ProductController::class ,'update'])->name('update.product')->middleware('auth');

// DELETE PRODUCT
Route::get('/product/delete/{id}', [ProductController::class ,'destroy'])->name('prodestroy');

// PELANGGAN
Route::get('/pelanggan', [PelangganController::class ,'index'])->name('indexl')->middleware('auth');
Route::get('/pelanggan/delete/{id}', [PelangganController::class ,'destroy'])->name('pedestroy');

// CREATE PELANGGAN
Route::get('/pelanggan/create', [PelangganController::class ,'create'])->name('createpl')->middleware('auth');
Route::post('/pelanggan/store', [PelangganController::class ,'store'])->name('pelastore')->middleware('auth');

// EDIT PELANGGAN
Route::put('/pelanggan/update/{id}', [PelangganController::class ,'update'])->name('pelupdate')->middleware('auth');

// KASIR
Route::get('/kasir', [KasirController::class ,'create'])->name('kasirs')->middleware('auth');
Route::post('/kasir/store', [KasirController::class ,'store'])->name('kasir.store')->middleware('auth');


