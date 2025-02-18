<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('post',PostController::class)->middleware('check_auth');
Route::post('login',[AuthController::class,'login'])->name('proseslogin');
Route::post('register',[AuthController::class,'register'])->name('prosesregister');