<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromocodeController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/create/user', [HomeController::class,'store']);

Route::post('/login/user', [HomeController::class,'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group( function () {
    
    Route::get('/logout/user', [HomeController::class,'logout']);

    Route::get('/get_categories',[CategoryController::class,'getcat']);

    Route::get('/get_products', [ProductController::class,'getprod']);

    Route::post('/addtocart', [CartController::class,'adtcrt']);

    Route::get('/mycart', [CartController::class,'mycart']);

    Route::post('/mycart/modification', [CartController::class,'mycartmodi']);

    Route::post('/add_address', [HomeController::class,'addadd']);

    Route::get('/get_address', [HomeController::class,'getadd']);
    
    Route::get('/add_wishlist', [WishlistController::class,'store']);

    Route::get('/get_wishlist', [WishlistController::class,'getwish']);

    Route::get('/wishlist/remove', [WishlistController::class,'removewish']);

    Route::get('/get_promocode', [PromocodeController::class,'getpromo']);

    Route::get('/add_promocode', [PromocodeController::class,'addpromo']);

    Route::post('/add_order', [OrderController::class,'addorder']);

    Route::get('/get_order', [OrderController::class,'getorder']);

    Route::get('/get_order/separatedata', [OrderController::class,'getorderseparatedata']);

    Route::get('/order/cancel', [OrderController::class,'canorder']);

    Route::post('/change_password', [HomeController::class,'changepassword']);

    Route::get('/popular/product', [ProductController::class,'popproduct']);
});

Route::post('/forgotpassword/sendotp', [HomeController::class,'sendotp']);
Route::post('/forgotpassword/otp/submit', [HomeController::class,'otpsubmit']);

Route::get('/savan', function(){
    return response()->json(['message'=>'ok'],200);
});