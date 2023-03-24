<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ApiUserController;
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

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout',[ApiUserController::class,'logout']);

    //Check for token
    Route::post('yoxla',[ApiUserController::class,'yoxla']);

    //Search
    Route::post('merchants',[AdminController::class,'merchantS']);
    Route::post('brands',[AdminController::class,'brandS']);
    Route::post('categorys',[AdminController::class,'categoryS']);

    //Add
    Route::post('addproduct',[AdminController::class,'addproduct']);
    Route::post('addbrand',[AdminController::class,'addbrand']);
    Route::post('addmerchant',[AdminController::class,'addmerchant']);

    //Delete
    Route::post('deletebrand/{id}',[AdminController::class,'deletebrand']);

    //Load tables
    Route::post('loadbrands',[AdminController::class,'loadbrands']);
});
Route::post('register',[ApiUserController::class,'register']);
Route::post('login',[ApiUserController::class,'login']);

