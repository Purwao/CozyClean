<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Models\OrderDetail;
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

//orders
Route::resource('/orders', OrderController::class);

//order_details
Route::resource('/orderdetails',OrderDetailController::class);

//types
Route::resource('/types',TypeController::class);

//services
Route::resource('/services', ServiceController::class);
                         
//users
Route::resource('/users',UserController::class);
Route::post('/login',[UserController::class,'login']);
Route::post('/register',[UserController::class,'register']);