<?php

use App\Http\Controllers\Api\AuthController;
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

Route::controller(AuthController::class)->group(function (){
    Route::post('/register','register');
    Route::post('/login','login');
//    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
//    Route::post('/reset-password', 'resetPassword')->name('password.reset');
    Route::post('/send', 'sendResetLink')->name('forgot.password');
    Route::post('/reset', 'resetPassword')->name('reset.password');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', 'logout');
        Route::post('/verify', 'verifyEmail');
        Route::post('/2fa', 'verify2FACode');
        Route::post('/refresh-token', 'refreshToken');
        Route::post('/resend2attempt', 'resend2attempt');
        Route::post('/resend-verification-code', 'resendVerificationCode')->middleware(['auth', 'throttle:2,10']);
    });

});




