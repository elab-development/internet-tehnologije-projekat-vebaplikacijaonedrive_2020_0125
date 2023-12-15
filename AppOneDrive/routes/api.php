<?php

use App\Http\Controllers\FirmController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
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


// User routes:
Route::resource('users', UserController::class);
Route::post('/users/add', [UserController::class, 'store']);
Route::put('/users/update/{id}', [UserController::class, 'update']);
Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);

// Firm routes:
Route::resource('firm', FirmController::class);
Route::post('/firm/add', [FirmController::class, 'store']);
Route::put('/firm/update/{PIB}', [FirmController::class, 'update']);
Route::delete('/firm/delete/{PIB}', [FirmController::class, 'destroy']);

//Member routes:
Route::get('/members/{PIB}', [MemberController::class, 'show']);
Route::post('/members', [MemberController::class, 'store']);
Route::put('/members/{userId}/{PIB}', [MemberController::class, 'update']);
Route::delete('/members/{userId}/{PIB}', [MemberController::class, 'destroy']);