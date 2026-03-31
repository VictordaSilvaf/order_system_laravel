<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
})->name('ping');

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthenticationController::class, 'login'])->name('v1.login');
    Route::post('/register', [AuthenticationController::class, 'register'])->name('v1.register');
    Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum')->name('v1.logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum')->name('v1.user');


    Route::resource('order', OrderController::class)->middleware('auth:sanctum')->names([
        'index' => 'v1.orders.index',
        'store' => 'v1.orders.store',
        'show' => 'v1.orders.show',
    ]);
});
