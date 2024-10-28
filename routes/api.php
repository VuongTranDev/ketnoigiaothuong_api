<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CompanyCategoryController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
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

Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'slug not exist!'
    ], 404);
});

Route::apiResource('/company', CompaniesController::class);
Route::apiResource('/new', NewsController::class);
Route::apiResource('/category', CategoriesController::class);
Route::apiResource('/company-category', CompanyCategoryController::class);
Route::apiResource('/address', AddressController::class);
