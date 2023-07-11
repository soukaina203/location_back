<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\rentalController;
use App\Http\Controllers\userController;
use App\Http\Controllers\carController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    Route::resources([
        'rental' => rentalController ::class,
        'user' => userController ::class,
        'car' => carController::class,

    ]);




});

