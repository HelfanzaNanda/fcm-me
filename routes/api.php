<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('create-token', [ApiController::class, 'createToken']);
Route::post('send-notif', [ApiController::class, 'sendNotif']);
Route::get('history', [ApiController::class, 'history']);