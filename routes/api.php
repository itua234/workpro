<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get("/sendcode/{id}", [AuthController::class, "sendcode"]);

Route::get("/verify/{verification_code}", [AuthController::class, "verifyUser"]);

Route::post("/reset", [AuthController::class, "resetPassword"]);

Route::get("/verify/reset/token/", [AuthController::class, "verifyResetPasswordToken"]);

//protected route using Laravel Sanctum
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post("/logout", [AuthController::class, "logout"]);
});

