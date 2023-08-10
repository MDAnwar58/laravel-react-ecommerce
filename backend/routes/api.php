<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// auth route
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// Route::post('/confirmation-email', [AuthController::class, 'confirmationEmail']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// * forget password
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
// * reset password
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::controller(BrandController::class)->group(function () {
    Route::get('/get-brand', 'getBrand');
    Route::post('/brand-store', 'store');
    Route::get('/brand-edit/{id}', 'edit');
    Route::post('/brand-update/{id}', 'update');
    Route::delete('/brand-destroy/{id}', 'destroy');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/get-category', 'getCategory');
    Route::post('/category-store', 'store');
    Route::get('/category-edit/{id}', 'edit');
    Route::post('/category-update/{id}', 'update');
    Route::delete('/category-destroy/{id}', 'destroy');
});

Route::controller(SubCategoryController::class)->group(function () {
    Route::get('/get-sub_category', 'getSubCategory');
    Route::get('/get-category-for-subcategory', 'getCategoryForSubCategory');
    Route::post('/sub_category-store', 'store');
    Route::get('/sub_category-edit/{id}', 'edit');
    Route::post('/sub_category-update/{id}', 'update');
    Route::delete('/sub_category-destroy/{id}', 'destroy');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/get-product', 'getProduct');
    Route::get('/get-sub-category-for-store-product', 'getSubCategoryForStoreProduct');
    Route::post('/product-store', 'store');
    Route::get('/product-edit/{id}', 'edit');
    Route::post('/product-update/{id}', 'update');
    Route::delete('/product-destroy/{id}', 'destroy');
});
