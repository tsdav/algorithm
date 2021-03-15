<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\User;
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
Route::group(['prefix'=>'admin','middleware'=>['auth:sanctum,role:admin']],function () {
    Route::get('/user/{id}', function ($id) {
        return new UserResource(User::findOrFail($id));
    });
    Route::group(['prefix'=>'products'],function () {
        Route::get('all',[ProductController::class, 'getAll']);
        Route::post('create',[ProductController::class, 'store']);
        Route::put('{id}',[ProductController::class, 'update']);
        Route::delete('{id}',[ProductController::class, 'destroy']);
        Route::get('{id}', function ($id) {
            return new ProductResource(Products::findOrFail($id));
        });


    });
    Route::get('categories/{id}', function ($id) {
        return new CategoryResource(Category::findOrFail($id));
    });
});
Route::group(['prefix'=>'user','middleware'=>['auth:sanctum,role:user']],function () {
    Route::group(['prefix'=>'products'],function () {
        Route::get('all',[ProductController::class, 'getAll']);
        Route::get('bought',[ProductController::class,'getProds']);
        Route::get('products/{id}/download',[ProductController::class,'downloadImage'])->name('file.download.index');
        Route::get('{id}',[ProductController::class, 'show']);
        Route::post('buy',[ProductController::class,'buy']);
    });
});
    Route::group(['prefix'=>'categories'], function (){
        Route::get('all',[CategoryController::class, 'getAll']);
    });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
