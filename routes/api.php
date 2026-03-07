<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentAuthController;
use App\Http\Controllers\Api\ContentApiController;

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

// Public Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [StudentAuthController::class, 'register']);
    Route::post('/login', [StudentAuthController::class, 'login']);
});

// Protected Routes (Student JWT)
Route::middleware('auth:api-student')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/me', [StudentAuthController::class, 'me']);
        Route::post('/logout', [StudentAuthController::class, 'logout']);
        Route::post('/refresh', [StudentAuthController::class, 'refresh']);
    });

    Route::prefix('content')->group(function () {
        Route::get('/categories', [ContentApiController::class, 'categories']);
        Route::get('/courses', [ContentApiController::class, 'courses']);
        Route::get('/courses/{id}', [ContentApiController::class, 'courseDetails']);
        Route::get('/subjects/{id}', [ContentApiController::class, 'subjectDetails']);
    });

    Route::prefix('learning')->group(function () {
        Route::get('/my-courses', [ContentApiController::class, 'myCourses']);
    });

    Route::prefix('quiz')->group(function () {
        Route::get('/{id}', [ContentApiController::class, 'quizDetails']);
        Route::post('/{id}/submit', [ContentApiController::class, 'submitQuiz']);
    });

    Route::prefix('orders')->group(function () {
        Route::post('/checkout', [ContentApiController::class, 'checkout']);
        Route::get('/history', [ContentApiController::class, 'orderHistory']);
    });
});
