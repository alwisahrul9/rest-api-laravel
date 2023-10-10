<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('data')->group(function () {
    Route::get('/', [ContactController::class, 'index']);
    Route::post('/', [ContactController::class, 'store']);
    Route::get('/{id}', [ContactController::class, 'show']);
    Route::post('/{id}', [ContactController::class, 'update']);
    Route::delete('/{id}', [ContactController::class, 'destroy']);
});