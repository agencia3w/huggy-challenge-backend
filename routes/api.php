<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReaderController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'authenticate']);


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('readers', [ReaderController::class, 'index']);
    Route::post('readers', [ReaderController::class, 'store']);
    Route::put('readers/{id}', [ReaderController::class, 'update']);
    Route::delete('readers/{id}', [ReaderController::class, 'destroy']);
});
