<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ClientController;
use App\Models\LeadType;
use App\Models\Privilege;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

Route::group(['prefix' => 'leads', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [LeadController::class, 'create']);
    Route::put('/{id}', [LeadController::class, 'update']);
    Route::delete('/{id}', [LeadController::class, 'destroy']);
    Route::get('/', [LeadController::class, 'index']);
    Route::get('/user', [LeadController::class, 'getLeadsByUserId']);
    Route::get('/{id}', [LeadController::class, 'show']);
});

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::group(['prefix' => 'events', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [EventController::class, 'create']);
    Route::get('/', [EventController::class, 'getAll']);
    Route::get('/user', [EventController::class, 'getEventsByUserId']);
    Route::get('/{id}', [EventController::class, 'find']);
    Route::put('/{id}', [EventController::class, 'update']);
    Route::delete('/{id}', [EventController::class, 'delete']);
});

Route::group(['prefix' => 'clients', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [ClientController::class, 'create']);
    Route::get('/', [ClientController::class, 'getAll']);
    Route::get('/user', [ClientController::class, 'getClientsByUserId']);
    Route::get('/{id}', [ClientController::class, 'find']);
    Route::put('/{id}', [ClientController::class, 'update']);
    Route::delete('/{id}', [ClientController::class, 'delete']);
});

Route::get('/lead-types', function () {
    return response()->json(LeadType::get());
});

Route::get('/privileges', function () {
    return response()->json(Privilege::get());
});