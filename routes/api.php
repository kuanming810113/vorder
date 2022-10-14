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