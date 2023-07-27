<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\rentalController;
use App\Http\Controllers\userController;
use App\Http\Controllers\carController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/signUp', [userController::class, 'signup']);
Route::post('/login', [userController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::resources([
        'rental' => rentalController ::class,
        'user' => userController ::class,
        'car' => carController::class,
        'review' => ReviewController::class,

    ]);

    Route::get('filterType',[carController::class,'getDistinctTypes']);
    Route::get('carsForAdmin',[carController::class,'carsForAdmin']);
    Route::post('car/uploadImg/{id}',[carController::class,'uploadImgs']);


});
Route::get('topCars',[carController::class,'BestDeals']);
Route::get('reviews/get',[ReviewController::class,'index']);

