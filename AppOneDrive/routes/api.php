<?php

use App\Http\Controllers\API\AuthController;
use App\Graph\OneDriveController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaginationFirmController;
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

// Forgot password route:
Route::post('/forgotPassword', [UserController::class, 'sendResetLink']);
 
// Reset password route:
Route::match(['get', 'post'], '/resetPassword', [UserController::class, 'resetPassword']);

Route::group(['middleware' => ['auth:sanctum']], function() {
        
    // User routes:
    Route::resource('users', UserController::class);
    Route::get('users/{id}/firms',[UserFirmController::class,'index']);
    Route::put('users/changePassword/{id}', [UserController::class, 'changePassword']);

    // Firm routes:
    Route::get('/firms/{PIB}', [FirmController::class, 'show']);
    Route::post('/firms', [FirmController::class, 'store']);
    Route::put('/firms/{PIB}', [FirmController::class, 'update'])->middleware(Privileges::class . ':admin');;
    Route::delete('/firms/{PIB}', [FirmController::class, 'destroy'])->middleware(Privileges::class . ':admin');;

    //Member routes:

    Route::get('/members/pagination/{PIB}/{perPage}/{page?}', [MemberController::class, 'showPagination']);
    Route::get('/members/searchMembers/{PIB}/{value}', [MemberController::class, 'searchMembers']);
    Route::get('/members/role/{PIB}', [MemberController::class, 'returnMemberRole']);
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
        //$localFilePath = 'C:\Users\darek\Downloads\test.txt';
        //file_put_contents($localFilePath, $response);
        //
        //return response()->json([], 200);
        return response()->json($response, 200);
    });

    Route::post('/firms/files/{firmName}/{firmitem}',function(Request $req,$firmName,$firmItem){
        $response=app(OneDriveController::class)->uploadFileInFirm($req,$firmName,$firmItem);
        return response()->json(json_decode($response->getBody()), $response->getStatusCode());
    })->middleware(Privileges::class . ':adminORwrite');

    Route::delete('/firms/files/{firmName}/{firmitem}',function($firmName,$firmItem){
        $response=app(OneDriveController::class)->deleteItemInFirm($firmName,$firmItem);
        return response()->json([], $response->getStatusCode());
    })->middleware(Privileges::class . ':adminORwrite');

    Route::patch('/firms/files/{firmName}/{firmitem}',function(Request $req,$firmName,$firmItem){
        $response=app(OneDriveController::class)->renameFileInFirm($firmName,$firmItem,$req->input('name'));
        return response()->json(json_decode($response->getBody()), $response->getStatusCode());
    })->middleware(Privileges::class . ':adminORwrite');

    //Logout route:
    Route::post('/logout', [AuthController::class, 'logout']);

    //pagination
    Route::get('/firms/pagination/all/{perPage}/{page?}', [PaginationFirmController::class, 'index']);
    Route::get('/firms/pagination/owner/{perPage}/{page?}', [PaginationFirmController::class, 'indexOwner']);
    Route::get('/firms/pagination/member/{perPage}/{page?}', [PaginationFirmController::class, 'indexMember']);
});



