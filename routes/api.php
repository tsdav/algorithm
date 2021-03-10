<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'products','middleware'=>'auth:sanctum'], function () {

    Route::middleware(['role:user'])->group(function () {
        Route::get('all',[ProductController::class, 'getAll']);
        Route::get('{id}',[ProductController::class, 'show']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('all',[ProductController::class, 'getAll']);
        Route::post('create',[ProductController::class, 'store']);
        Route::get('{id}',[ProductController::class, 'show']);
        Route::put('{id}',[ProductController::class, 'update']);
        Route::delete('{id}',[ProductController::class, 'destroy']);
    });
});

Route::group(['prefix'=>'categories'], function (){
    Route::get('all',[CategoryController::class, 'getAll']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
