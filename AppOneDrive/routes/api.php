<?php

use App\Graph\GraphApiCaller;
use App\Graph\GraphHelper;
use App\Graph\OneDriveController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFirmController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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



Route::get('nesto',function(Response $res){
});


// User routes:
Route::resource('users', UserController::class);
Route::get('users/{id}/firms',[UserFirmController::class,'index']);
// Route::post('/users/add', [UserController::class, 'store']);
// Route::put('/users/update/{id}', [UserController::class, 'update']);
// Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);

// Firm routes:
Route::resource('firms', FirmController::class);
Route::delete('/firms/files/{firmName}/{firmitem}',function($firmName,$firmItem){
    $response=app(OneDriveController::class)->deleteItemInFirm($firmName,$firmItem);
    return response()->json($response, 200);
});
// Route::post('/firm/add', [FirmController::class, 'store']);
// Route::put('/firm/update/{PIB}', [FirmController::class, 'update']);


//Member routes:
Route::get('/members/{PIB}', [MemberController::class, 'show']);
Route::post('/members', [MemberController::class, 'store']);
Route::put('/members/{userId}/{PIB}', [MemberController::class, 'update']);
Route::delete('/members/{userId}/{PIB}', [MemberController::class, 'destroy']);