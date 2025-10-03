<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/{id}/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'leads', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [LeadController::class, 'create']);
    Route::put('/{id}', [LeadController::class, 'update']);
    Route::delete('/{id}', [LeadController::class, 'destroy']);
    Route::get('/', [LeadController::class, 'index']);
    Route::get('/{id}', [LeadController::class, 'show']);
});
Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});