<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\
{
    AuthController,
    ProfileController,
    WalletController
};

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

Route::post("/register", [AuthController::class, "store"]);

Route::post("/login", [AuthController::class, "login"]);

Route::get("/sendcode/{email}", [AuthController::class, "sendcode"]);

Route::post("/verify/code", [AuthController::class, "verifyUser"]);

Route::post("/reset", [AuthController::class, "resetPassword"]);

Route::post("/verify/reset/token/", [AuthController::class, "verifyResetPasswordToken"]);

Route::post("/password-reset", [AuthController::class, "password_reset"]);

//protected route using Laravel Sanctum
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post("/logout", [AuthController::class, "logout"]);

    Route::post("/refresh", [AuthController::class, "refresh"]);

    Route::post("/change-password", [AuthController::class, "change_password"]);

    


    Route::post("/delete", [ProfileController::class, "delete"]);

    Route::post("/save-account-details", [ProfileController::class, "saveAccountDetails"]);
    
    Route::post("/save-next-of-kin-details", [ProfileController::class, "saveNextOfKinDetails"]);




    Route::post("/getwallet", [WalletController::class, "getWallet"]);
});

