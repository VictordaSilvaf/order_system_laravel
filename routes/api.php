<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/ping', function () {
        return response()->json(['message' => 'pong']);
    });

    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');


    Route::resource('order', OrderController::class)->middleware('auth:sanctum');
});
