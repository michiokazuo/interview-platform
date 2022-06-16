<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CVController;
use App\Http\Controllers\API\InterviewController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\QATController;
use App\Http\Controllers\API\RecruitmentNewsController;
use App\Http\Controllers\API\RecruitmentProcessController;
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
    Route::get('/blog-by-user/{id}', [BlogController::class, 'showAllByUser']);
    Route::apiResource('comment', CommentController::class);

    Route::apiResource('project', ProjectController::class);
    Route::post('/project-change/{id}', [ProjectController::class, 'changeStatus']);
    Route::apiResource('project-process', RecruitmentProcessController::class);
    Route::post('/project-process-change/{id}', [RecruitmentProcessController::class, 'changeStatus']);
    Route::apiResource('project-news', RecruitmentNewsController::class);
    Route::post('/project-news-change/{id}', [RecruitmentNewsController::class, 'changeStatus']);
    Route::get('/project-news-edit/{id}', [RecruitmentNewsController::class, 'showToEdit']);
    Route::get('/news-by-user/{id}', [RecruitmentNewsController::class, 'showAllByUser']);
    Route::get('/news-by-project/{id}', [RecruitmentNewsController::class, 'showAllByProject']);

    Route::apiResource('interview', InterviewController::class)->except(['index', 'update']);
    Route::post('/interview-update/{id}', [InterviewController::class, 'update']);
    Route::get('/interviews-by-user/{id}', [InterviewController::class, 'showAllByUser']);
    Route::get('/interview-find-news/{id}', [InterviewController::class, 'findByNewsId']);
    Route::get('/interviews-by-news/{id}', [InterviewController::class, 'showAllByNews']);
    Route::get('/interview-edit/{id}', [InterviewController::class, 'showToEdit']);
    Route::post('/create-test/{id}', [InterviewController::class, 'createTest']);
    Route::post('/save-test/{id}', [InterviewController::class, 'saveTestRS']);
    
    Route::get('/qat/tags', [QATController::class, 'showTags']);
    Route::post('/qat/questions', [QATController::class, 'showQuestions']);
    
    Route::get('/admin/all-user', [AdminController::class, 'index']);
    Route::post('/admin/active-company/{id}', [AdminController::class, 'activeCompany']);
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'destroy']);
    Route::post('/admin/crawler', [AdminController::class, 'crawler']);
});

