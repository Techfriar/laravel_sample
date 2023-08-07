<?php

namespace App\Http\Controllers;
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


/**
 * User login flow
 */
Route::post('login', [AuthController::class, 'logIn']);
Route::post('forgot_password', [AuthController::class, 'forgotPassword']);
Route::post('forgot_password_verify_otp', [AuthController::class, 'forgotPasswordVerifyOTP']);
Route::post('forgot_password_reset', [AuthController::class, 'forgotPasswordReset']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logOut']);

    /**
     * User crud flow
     */
    Route::post('create_user', [UserController::class, 'createUser']);
    Route::post('get_user', [UserController::class, 'getUserById']);
    Route::get('user_list', [UserController::class, 'listUsers']);
    Route::post('edit_user', [UserController::class, 'editUser']);   
    Route::post('delete_user', [UserController::class, 'deleteUser']);
    Route::post('my_profile', [UserController::class, 'myProfile']);
    
    /**
     * votting flow
     */
    Route::post('vote_now', [VottingController::class, 'doVote']);

    /**
     * Result flow
     */
    Route::post('get_result', [ResultController::class, 'getResult']);
    Route::get('get_date', [ResultController::class, 'showResultMonth']);
});


