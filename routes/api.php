<?php

use App\Http\Controllers\feedController;
use App\Http\Controllers\orderContoller;
use App\Http\Controllers\scrapDataController;
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

Route::post('/feed', [feedController::class, 'index']);
Route::post('/account', [scrapDataController::class, 'index']);
Route::post('/account/{account_name}', [scrapDataController::class, 'update_last_scrap']);
Route::get('/account/{account_name}', [scrapDataController::class, 'get_last_scrap']);
Route::get('/orders/{account_name}', [orderContoller::class, 'get_orders_by_ac']);
Route::get('/orders', [orderContoller::class, 'get_all_orders']);
