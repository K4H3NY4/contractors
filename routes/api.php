<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return 'Welcome to my Laravel application!';
});


//Login, Register, Forgot-Password
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/forgot-password', [ResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetController::class, 'reset']);

//Project Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class)->except(['index', 'show']);
});

Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);

//Payments
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('payments', PaymentController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


