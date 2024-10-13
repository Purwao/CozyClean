<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TypeController;
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
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'create']);
Route::put('/orders/{id}', [OrderController::class, 'update']);
Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

//order_details
Route::get('/orderdetails/{id}',[OrderDetailController::class,'show']);
Route::get('/orderdetails', [OrderDetailController::class,'index']);

//types
Route::get('/types',[TypeController::class,'index']);
Route::get('/types/{id}',[TypeController::class,'show']);
Route::post('/types',[TypeController::class,'create']);
Route::post('/types/{id}',[TypeController::class,'edit']);
Route::delete('/types/{id}',[TypeController::class,'destroy']);

//Services
Route::get('/services',[ServiceController::class,'index']);
Route::get('/services/{id}',[ServiceController::class,'show']);
Route::post('/services',[ServiceController::class,'create']);
Route::put('/services/{id}',[ServiceController::class,'edit']);
Route::delete('/services/{id}',[ServiceController::class,'destroy']);
                                  

