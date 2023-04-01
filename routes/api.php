<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\FrontController;
use Faker\Core\File;
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
    Route::post('addcategory',[AdminController::class,'addcategory']);

    //Delete
    Route::post('deletebrand/{id}',[AdminController::class,'deletebrand']);
    Route::post('deletemerchant/{id}',[AdminController::class,'deletemerchant']);
    Route::post('deletecategory/{id}',[AdminController::class,'deletecategory']);
    Route::post('deleteproduct/{id}',[AdminController::class,'deleteproduct']);

    //Load tables
    Route::post('loadbrands',[AdminController::class,'loadbrands']);
    Route::post('loadmerchants',[AdminController::class,'loadmerchants']);
    Route::post('loadcategories',[AdminController::class,'loadcategories']);
    Route::post('loadproducts',[AdminController::class,'loadproducts']);

});
Route::post('register',[ApiUserController::class,'register']);
Route::post('login',[ApiUserController::class,'login']);
Route::get('/images/{filename}', function ($filename) {
    $path = storage_path('app/images/' . $filename);
    return response()->file($path);
});
Route::get('/images/{type}/{filename}', function ($type,$filename) {
    $path = storage_path('app/images/' .$type . '/' . $filename);
    return response()->file($path);
});
//Frontt
Route::get('loadcat/{slug}',[FrontController::class,'loadcategory']);
Route::get('loadcats',[FrontController::class,'loadcategories']);
Route::get('loadallcats',[FrontController::class,'loadallcategories']);
Route::get('loadprs',[FrontController::class,'loadprs']);
Route::post('loadpras',[FrontController::class,'loadpras']);
Route::post('loadmrpras',[FrontController::class,'loadmrpras']);
Route::post('loadbrpras',[FrontController::class,'loadbrpras']);
Route::get('loadbr/{slug}',[FrontController::class,'loadbrand']);
Route::get('loadbrs/{id}',[FrontController::class,'loadbrands']);
Route::get('loadbras/{id}',[FrontController::class,'loadbras']);
Route::get('loadmrs/{id}',[FrontController::class,'loadmerchants']);
Route::get('loadmer/{slug}',[FrontController::class,'loadmerchant']);
Route::get('loadmras/{id}',[FrontController::class,'loadmras']);

