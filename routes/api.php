<?php

use App\Http\Controllers\Api\AccessTokensController;
use App\Http\Controllers\DoctorController;
use App\Models;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;


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
    return $request->user();
});

Route::controller(DoctorController::class)->group(function(){
    Route::post('register','register');
    Route::post('login','login');
});
