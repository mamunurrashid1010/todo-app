<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


// These routes are accessible without authentication.
Route::post('/register', [AuthController::class, 'register']); // User registration
Route::post('/login', [AuthController::class, 'login']); // User login


// These routes are accessible only to authenticated users via Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'user']);       // Get authenticated user details
    Route::post('/logout', [AuthController::class, 'logout']);  // User logout

});