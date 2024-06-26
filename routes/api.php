<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\rentalController;
use App\Http\Controllers\userController;
use App\Http\Controllers\carController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\productController;



Route::post('/signUp', [userController::class, 'signup']);
Route::post('/login', [userController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/rentals', [rentalController::class, 'store']);

    Route::get('/user/{id}', [userController::class, 'show']);
    Route::patch('/user/{id}', [userController::class, 'update']);

    Route::get('/rentals/{id}', [rentalController::class, 'RentalsOfAUser']);

    Route::get('/carForUsers', [carController::class, 'CarsForUser']);
    Route::get('/select', [rentalController::class, 'selecteData']);
    Route::post('user/uploadImg/{id}', [userController::class, 'uploadImgs']);

    Route::get('/search/{key}', [carController::class, 'search']);
    Route::get('/search/user/{key}', [userController::class, 'search']);
    Route::get('/search/review/{key}', [ReviewController::class, 'search']);
    Route::get('/car/{id}', [carController::class, 'show']);
    Route::post('/review/create', [ReviewController::class, 'store']);
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::resource('/review', ReviewController::class);
    Route::get('/review/user/{userId}', [ReviewController::class, 'userReviews']);

});
Route::post('/logoutUser', [userController::class, 'logout'])->middleware('auth:sanctum');
Route::group(['middleware' => ['auth:sanctum', 'isAdmin']], function () {


    Route::resources([
        'rental' => rentalController::class,
        'user' => userController::class,
        'car' => carController::class,

    ]);
    // Route::get('/select',[rentalController::class,'selecteData']);
    Route::post('/logout', [userController::class, 'logout']);
    Route::get('notProcessed', [rentalController::class, 'NotProcessed']);
    Route::get('carsForAdmin', [carController::class, 'carsForAdmin']);
    Route::post('car/uploadImg/{id}', [carController::class, 'uploadImgs']);
    Route::post('car/uploadImg', [carController::class, 'uploadCars']);
    // Route::post('user/uploadImg/{id}', [userController::class, 'uploadImgs']);
    Route::post('/logout', [userController::class, 'logout']);
    Route::post('/pro', [rentalController::class, 'processed']);
});
// for users

Route::resource('products',productController::class);

Route::get('topCars', [carController::class, 'BestDeals']);
Route::get('reviews/get', [ReviewController::class, 'index']);
Route::get('reviews/custom', [ReviewController::class, 'custom']);
