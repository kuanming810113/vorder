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
// Route::group([
//     //'middleware' => 'auth:sanctum',
//     'prefix' => '/user'
// ], function () {
// 	Route::post('/get/{action_type}', [UserController::class, 'get']);
//     Route::post('/register', [UserController::class, 'register']);
//     Route::post('/logout', [UserController::class, 'register']);
// });
Route::group([
    //'middleware' => 'auth:sanctum',
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