<?php

use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CVController;
use App\Http\Controllers\AuthController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'userProfile']);
        Route::post('/user', [AuthController::class, 'updateUser']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });

    Route::get('/cv', [CvController::class, 'show']);
    Route::post('/cv', [CvController::class, 'store']);
    
    Route::apiResource('blog', BlogController::class);
    Route::get('/blog-edit/{id}', [BlogController::class, 'showToEdit']);
    Route::get('/blog-by-user', [BlogController::class, 'showAllByUser']);
    Route::apiResource('comment', CommentController::class);
});

