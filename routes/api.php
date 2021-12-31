<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Unprotected Routes
Route::prefix('/user')->group(function () {
    Route::get('/', [AuthController::class, 'all']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/user/logout', [AuthController::class, 'logout']);

    Route::apiResource('products', 'ProductController');
    Route::prefix('/products')->group(function () {
        Route::post('/search', [ProductController::class, 'search']);
        Route::post('/sort', [ProductController::class, 'sort']);
    });
});
