<?php

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

// Route::middleware('auth:sanctum')->get('/puser', function (Request $request) {
//     return $request->user();
// });

use App\Http\Controllers\UserController;
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => '/user'
], function () {
	Route::post('/update/{action_type}', [UserController::class, 'update']);
	Route::post('/logout', [UserController::class, 'logout']);
});
Route::group([
    'prefix' => '/user'
], function () {
	Route::post('/get/{action_type}', [UserController::class, 'get']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

use App\Http\Controllers\AccountController;
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'account'
], function () {
    Route::post('/index', [AccountController::class, 'index']);
    Route::post('/update/{action_type}', [AccountController::class, 'update']);
    Route::post('/insert', [AccountController::class, 'insert']);
    Route::post('/delete', [AccountController::class, 'delete']);
});
Route::group([
    'prefix' => '/account'
], function () {
    Route::post('/get/{action_type}', [AccountController::class, 'get']);
});

use App\Http\Controllers\CompanyController;
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => '/company'
], function () {
    Route::post('/index', [CompanyController::class, 'index']);
    Route::post('/update/{action_type}', [CompanyController::class, 'update']);
    Route::post('/insert', [CompanyController::class, 'insert']);
    Route::post('/delete', [CompanyController::class, 'delete']);
});
Route::group([
    'prefix' => '/company'
], function () {
    Route::post('/get/{action_type}', [CompanyController::class, 'get']);
});


use App\Http\Controllers\ProductController;
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => '/product'
], function () {
	Route::post('/index', [ProductController::class, 'index']);
	Route::post('/update/{action_type}', [ProductController::class, 'update']);
	Route::post('/insert', [ProductController::class, 'insert']);
	Route::post('/delete', [ProductController::class, 'delete']);
});
Route::group([
    'prefix' => '/product'
], function () {
	Route::post('/get/{action_type}', [ProductController::class, 'get']);
});


use App\Http\Controllers\ProductStyleController;
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => '/product_style'
], function () {
	Route::post('/delete', [ProductStyleController::class, 'delete']);
});
Route::group([
    'prefix' => '/product_style'
], function () {
	Route::post('/get/{action_type}', [ProductStyleController::class, 'get']);
});


use App\Http\Controllers\GoodsCategoryController;
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => '/goods_category'
], function () {
    Route::post('/index', [GoodsCategoryController::class, 'index']);
    Route::post('/update/{action_type}', [GoodsCategoryController::class, 'update']);
    Route::post('/insert', [GoodsCategoryController::class, 'insert']);
    Route::post('/delete', [GoodsCategoryController::class, 'delete']);
});
Route::group([
    'prefix' => '/goods_category'
], function () {
    Route::post('/get/{action_type}', [GoodsCategoryController::class, 'get']);
});


use App\Http\Controllers\GoodsComboController;
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => '/goods_combo'
], function () {
    Route::post('/index', [GoodsComboController::class, 'index']);
    Route::post('/update/{action_type}', [GoodsComboController::class, 'update']);
    Route::post('/insert', [GoodsComboController::class, 'insert']);
    Route::post('/delete', [GoodsComboController::class, 'delete']);
});
Route::group([
    'prefix' => '/goods_combo'
], function () {
    Route::post('/get/{action_type}', [GoodsComboController::class, 'get']);
});


use App\Http\Controllers\GoodsController;
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => '/goods'
], function () {
    Route::post('/index', [GoodsController::class, 'index']);
    Route::post('/update/{action_type}', [GoodsController::class, 'update']);
    Route::post('/insert', [GoodsController::class, 'insert']);
    Route::post('/delete', [GoodsController::class, 'delete']);
});
Route::group([
    'prefix' => '/goods'
], function () {
    Route::post('/get/{action_type}', [GoodsController::class, 'get']);
});