<?php

use Axilweb\Vaccine\Http\Controllers\Api\AuthController;
use Axilweb\Vaccine\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('api/vaccine')->group(function () {
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

    Route::post('login',[AuthController::class,'loginUser']);

    Route::post('/auth/register', [AuthController::class, 'createUser']);
    Route::post('/auth/login', [AuthController::class, 'loginUser']);
    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

    Route::post('/logout', [AuthController::class, 'logoutUser']);

    /**
     * search
     */

    Route::get('search-nid/{query}', [\Axilweb\Vaccine\Http\Controllers\Api\SearchController::class, 'searchByQuery']);
//getAllAvailableCentersList
    Route::get('available-vaccination-center', [\Axilweb\Vaccine\Http\Controllers\Api\VaccinationCenterController::class, 'getAllAvailableCentersList']);
    
    Route::get('vaccination-center-lists', [\Axilweb\Vaccine\Http\Controllers\Api\VaccinationCenterController::class, 'getAllAvailableCentersListAndScheduled']);

    Route::get('user-lists', [\Axilweb\Vaccine\Http\Controllers\Api\UsersController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        // Your authenticated routes go here
        Route::resource('users', UsersController::class, ['name' => 'admin.users']);
        //Route::post('/logout', [AuthController::class, 'logoutUser']);
        Route::resource('vaccination-center', \Axilweb\Vaccine\Http\Controllers\Api\VaccinationCenterController::class);
        Route::resource('day-capacity-limit-day-wise', \Axilweb\Vaccine\Http\Controllers\Api\VaccinationCenterCapacityLimitDayWiseController::class);

        Route::post('/auth/register-vaccine-user', [AuthController::class, 'registerVaccineUserFromAdmin']);
    });
});
