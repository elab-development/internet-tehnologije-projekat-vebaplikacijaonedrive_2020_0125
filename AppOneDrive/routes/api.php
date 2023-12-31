<?php

use App\Http\Controllers\API\AuthController;
use App\Graph\OneDriveController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFirmController;
use App\Http\Middleware\Member\Privileges;
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


//Register route:
Route::post('/register', [AuthController::class, 'register']);

//Login route:
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
        
    // User routes:
    Route::resource('users', UserController::class);
    Route::get('users/{id}/firms',[UserFirmController::class,'index']);

    // Firm routes:
    Route::post('/firms', [FirmController::class, 'store']);
    Route::put('/firms/{PIB}', [FirmController::class, 'update'])->middleware(Privileges::class . ':admin');;
    Route::delete('/firms/{PIB}', [FirmController::class, 'destroy'])->middleware(Privileges::class . ':admin');;

    //Member routes:
    Route::get('/members/{PIB}', [MemberController::class, 'show']);
    Route::post('/members', [MemberController::class, 'store'])->middleware(Privileges::class . ':admin');
    Route::put('/members/{userId}/{PIB}', [MemberController::class, 'update'])->middleware(Privileges::class . ':admin');
    Route::delete('/members/{userId}/{PIB}', [MemberController::class, 'destroy'])->middleware(Privileges::class . ':admin');

    //firms working with files
    Route::get('/firms/files/{firmName}',function($firmName){
        $response=app(OneDriveController::class)->getAllFilesInFirm($firmName);
        return response()->json(json_decode($response->getBody()), $response->getStatusCode());
    });

    Route::get('/firms/files/{firmName}/{firmitem}',function($firmName,$firmItem){
        $response=app(OneDriveController::class)->getDownloadLinkFileInFirm($firmName,$firmItem);
        return response()->json(json_decode($response->getBody()), $response->getStatusCode());
    });

    Route::get('/firms/files/{firmName}/{firmitem}/content',function($firmName,$firmItem){
        $response=app(OneDriveController::class)->getDownloadContentFileInFirm($firmName,$firmItem);
        //test example, it will return body of response to client !
        $localFilePath = 'C:\Users\darek\Downloads\test.txt';
        file_put_contents($localFilePath, $response);
        //
        //return response()->json([], 200);
        return response()->json($response, 200);
    });

    Route::put('/firms/files/{firmName}/{firmitem}',function($firmName,$firmItem){
        $response=app(OneDriveController::class)->uploadFileInFirm($firmName,$firmItem);
        return response()->json(json_decode($response->getBody()), $response->getStatusCode());
    })->middleware(Privileges::class . ':adminORwrite');

    Route::delete('/firms/files/{firmName}/{firmitem}',function($firmName,$firmItem){
        $response=app(OneDriveController::class)->deleteItemInFirm($firmName,$firmItem);
        return response()->json([], $response->getStatusCode());
    })->middleware(Privileges::class . ':admin');

    Route::patch('/firms/files/{firmName}/{firmitem}',function(Request $req,$firmName,$firmItem){
        $response=app(OneDriveController::class)->renameFileInFirm($firmName,$firmItem,$req->input('name'));
        return response()->json(json_decode($response->getBody()), $response->getStatusCode());
    })->middleware(Privileges::class . ':adminORwrite');

    //Logout route:
    Route::post('/logout', [AuthController::class, 'logout']);

});



