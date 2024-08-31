<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiBrandController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Brands Routes
Route::get('/brands',[ApiBrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brandid}',[ApiBrandController::class, 'indexById'])->name('brands.index');
Route::post('/brands',[ApiBrandController::class, 'store'])->name('brands.store');
Route::put('/brands/{brandId}', [ApiBrandController::class, 'update'])->name('brands.update');
Route::delete('/brands/{brands}',[ApiBrandController::class, 'destroy'])->name('brands.delete');
