<?php

use App\Http\Controllers\ColorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


///////////////////////////////////  Authentication Route ///////////////////////////////////
Route::controller(\App\Http\Controllers\AuthenticationController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });
});
///////////////////////////////////   End Authentication Route ///////////////////////////////////

// auth:sanctum
Route::middleware('auth:sanctum')->group(function () {

///////////////////////////////////  Profile Route ///////////////////////////////////////
    Route::controller(\App\Http\Controllers\ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/getCurrentUser', 'getCurrentUser');
        Route::PUT('/update', 'update');
        Route::get('/logout', 'logout');
        Route::delete('/delete', 'delete');
        Route::get('/refreshToken', 'refreshToken');
    });
///////////////////////////////////   End Profile Route ///////////////////////////////////

///////////////////////////////////  User Route ///////////////////////////////////
    Route::resource('/users', UserController::class)->except('edit', 'create', 'show');
///////////////////////////////////   End User Route ///////////////////////////////////

///////////////////////////////////////  Factory Route ///////////////////////////////////
    Route::resource('/factory', \App\Http\Controllers\FactoryController::class);
///////////////////////////////////   End Factory Route ///////////////////////////////////

//////////////////////////////////  Size Route ///////////////////////////////////
    Route::resource('/size', SizeController::class)->except('edit', 'create', 'show');
///////////////////////////////////   End Size Route ///////////////////////////////////

///////////////////////////////////////  Color Route ///////////////////////////////////
    Route::resource('/color', ColorController::class)->except('edit', 'create');
///////////////////////////////////   End Color Route ///////////////////////////////////

///////////////////////////////////  Product Route ///////////////////////////////////
    Route::controller(\App\Http\Controllers\ProductController::class)->prefix('product')->group(function () {
        Route::get('index', 'index');
        Route::get('show/{product}', 'show');
        Route::post('/store/{product?}', 'store');
        Route::post('/search', 'search');
    });
///////////////////////////////////   End Product Route ///////////////////////////////////

///////////////////////////////////  Product Route ///////////////////////////////////
    Route::controller(\App\Http\Controllers\StoreController::class)->prefix('store')->group(function () {
        Route::get('index', 'index');
        Route::post('store/{store?}', 'store');
        Route::get('show/{store}', 'show');
        Route::post('restore/{store}', 'restore');
        Route::post('/search', 'search');
    });
///////////////////////////////////   End Product Route ///////////////////////////////////

});


///////////////////////////////////////  Order Route ///////////////////////////////////
Route::resource('/order', OrderController::class)->except('edit', 'create', 'show', 'update');
Route::post('order/restore/{order}', [OrderController::class, 'restore']);
///////////////////////////////////   End Order Route ///////////////////////////////////
