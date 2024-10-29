<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\MessageController;



Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'getInfo']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->post('changeStatus/{id}', [AuthController::class, 'changeStatusUser']);

Route::middleware('auth:sanctum')->get('/news/statistics', [NewsController::class, 'statistics'])->name('news.statistics');
// Message
Route::middleware('auth:sanctum')->post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');
Route::middleware('auth:sanctum')->get('/messages/{userId}', [MessageController::class, 'getMessages'])->name('messages.get');
Route::middleware('auth:sanctum')->post('/messages/{messageId}/read', [MessageController::class, 'markAsRead'])->name('messages.read');



