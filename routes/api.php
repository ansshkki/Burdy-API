<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Unprotected Routes
Route::prefix('/user')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
});

//Protected Routes
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/user/logout',[AuthController::class,'logout']);

    Route::prefix('/products')->group(function(){
        Route::get('/index', [ProductController::class, 'index']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/show/{product}', [ProductController::class, 'show']);
        Route::put('/update/{product}', [ProductController::class, 'update']);
        Route::delete('/destroy/{product}', [ProductController::class, 'destroy']);
    });
});
