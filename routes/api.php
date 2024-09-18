<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiBrandController;
use App\Http\Controllers\api\ApiCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\CartController;
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


Route::prefix('categories')->group(function () {
    Route::get('/', [ApiCategoryController::class, 'index']);
    Route::post('/store', [ApiCategoryController::class, 'store']);
    Route::put('/update/{id}', [ApiCategoryController::class, 'update']);
    Route::delete('/delete/{id}', [ApiCategoryController::class, 'destroy']);
});

//Brands Routes
Route::get('/brands', [ApiBrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brandid}', [ApiBrandController::class, 'indexById'])->name('brands.index');
Route::post('/brands', [ApiBrandController::class, 'store'])->name('brands.store');
Route::put('/brands/{brandId}', [ApiBrandController::class, 'update'])->name('brands.update');
Route::delete('/brands/{brands}', [ApiBrandController::class, 'destroy'])->name('brands.delete');


Route::resource('/products', ProductController::class)->only('index', 'show');


Route::get('/categories/{id}/products', [ProductController::class, 'getProductsByCategory']);


Route::get('/cart', [CartController::class, 'cart']);
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::post('/add-item-to-cart', [CartController::class, 'addItemToCart']);
Route::post('/update-cart', [CartController::class, 'updateCart']);
Route::post('/delete-item', [CartController::class, 'deleteItem']);
Route::get('/checkout', [CartController::class, 'checkout']);
Route::post('/process-checkout', [CartController::class, 'processCheckout']);
