<?php

use App\Http\Controllers\DemoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/demo',[DemoController::class,'index']);
Route::post('/createcat',[DemoController::class,'createcat']);
Route::post('/createmer',[DemoController::class,'createmer']);
Route::post('/createbr',[DemoController::class,'createbr']);
Route::post('/createpr',[DemoController::class,'createpr']);
Route::post('/createinf',[DemoController::class,'createinf']);

