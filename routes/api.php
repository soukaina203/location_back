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
    Route::post('/rentals', [rentalController::class, 'store']);


    Route::get('/rentals/{id}', [rentalController::class, 'RentalsOfAUser']);

    Route::get('/carForUsers', [carController::class, 'CarsForUser']);
    Route::get('/select', [rentalController::class, 'selecteData']);

    Route::get('/search/{key}', [carController::class, 'search']);
    Route::get('/car/{id}', [carController::class, 'show']);
});
Route::post('/logoutUser', [userController::class, 'logout'])->middleware('auth:sanctum');
Route::group(['middleware' => ['auth:sanctum', 'isAdmin']], function () {


    Route::resources([
        'rental' => rentalController::class,
        'user' => userController::class,
        'car' => carController::class,
        'review' => ReviewController::class,

    ]);
    // Route::get('/select',[rentalController::class,'selecteData']);
    Route::post('/logout', [userController::class, 'logout']);
    Route::get('notProcessed', [rentalController::class, 'NotProcessed']);
    Route::get('carsForAdmin', [carController::class, 'carsForAdmin']);
    Route::post('car/uploadImg/{id}', [carController::class, 'uploadImgs']);
    Route::post('user/uploadImg/{id}', [userController::class, 'uploadImgs']);
    Route::post('/logout', [userController::class, 'logout']);
    Route::post('/pro/{id}', [rentalController::class, 'processedStatus']);
});
// for users



Route::get('topCars', [carController::class, 'BestDeals']);
Route::get('reviews/get', [ReviewController::class, 'index']);
