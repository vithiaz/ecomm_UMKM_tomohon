<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\MidtransController;
use App\Http\Controllers\User\MailRegistrationVerification;

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

Route::post('/midtrans-payment-callback', [MidtransController::class, 'payment_callback']);

Route::get('/registration-verification/{user_id}/{verify_token}', [MailRegistrationVerification::class, 'verify_user']);