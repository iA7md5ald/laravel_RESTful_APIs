<?php

use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\DomainController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\SettingController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//--------------------------------- Auth Module
Route::controller(AuthController::class)->group(function (){
   Route::post('/register' , 'register');
   Route::post('login' , 'login');
   Route::post('/logout' , 'logout')->middleware('auth:sanctum');

});

// -------------------------------- Settings Module
Route::get('/settings' , SettingController::class);
// -------------------------------- Cities Module
Route::get('/cities' , CityController::class);
// -------------------------------- Districts Module
Route::get('/districts/{city_id}' , DistrictController::class);
// -------------------------------- Messages Module
Route::post('/messages' , MessageController::class);
// -------------------------------- Domains Module
Route::get('/domains' , DomainController::class);
// -------------------------------- Ads Module
Route::prefix('/ads')->controller(AdController::class)->group(function (){
   Route::get('/' , 'index');
   Route::get('/latest' , 'latest');
   Route::get('/search' , 'search');
   Route::middleware('auth:sanctum')->group(function (){
      Route::post('/create' , 'create');
      Route::post('/update/{adId}' , 'update');
      Route::get('/delete/{adId}' , 'delete');
      Route::get('/userAds', 'showAds');
   });
});
