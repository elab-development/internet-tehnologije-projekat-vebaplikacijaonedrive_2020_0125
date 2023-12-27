<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFirmController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
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

//Register route:
Route::post('/register', [AuthController::class, 'register']);

//Login route:
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    // User routes:
    Route::resource('users', UserController::class);
    Route::get('users/{id}/firms',[UserFirmController::class,'index']);
    // Route::post('/users/', [UserController::class, 'store']);
    // Route::put('/users/{id}', [UserController::class, 'update']);
    // Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
    // Firm routes:
    Route::resource('firms', FirmController::class);
    // Route::post('/firm', [FirmController::class, 'store']);
    // Route::put('/firm/{PIB}', [FirmController::class, 'update']);
    // Route::delete('/firm/{PIB}', [FirmController::class, 'destroy']);
    
    //Member routes:
    Route::get('/members/{PIB}', [MemberController::class, 'show']);
    Route::post('/members', [MemberController::class, 'store']);
    Route::put('/members/{userId}/{PIB}', [MemberController::class, 'update']);
    Route::delete('/members/{userId}/{PIB}', [MemberController::class, 'destroy']);

    //Logout route:
    Route::post('/logout', [AuthController::class, 'logout']);
});
