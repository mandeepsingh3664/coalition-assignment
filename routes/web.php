<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [Product::class, 'index']);
Route::post('/products', [Product::class, 'store'])->name('products.store');
Route::post('/products/update', [Product::class, 'update'])->name('products.update');