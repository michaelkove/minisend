<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//    Route::middleware('auth:api')->group(function () {
    Route::get('user/active', [\App\Http\Controllers\Api\UserController::class, 'active'])->name('api.active');
    Route::get('user/{userId}/email/search', [\App\Http\Controllers\Api\EmailController::class, 'search'])->name('api.users.email.search');
    Route::apiResource('user', \App\Http\Controllers\Api\UserController::class)->names([
            'show' => 'api.user.show',
        ]
    );
    Route::apiResource('user.email', \App\Http\Controllers\Api\EmailController::class)->names([
            'show' => 'api.show',
        ]
    );;


    //        Route::apiResource('user.email', Api/EmailController::class);
//        Route::apiResource('user.recipient', Api/RecipientController::class);
//        Route::apiResource('user.email.attachment', Api/AttachmentController::class);

//    });


