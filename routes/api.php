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

// Admin API Layer (NEW)
Route::prefix('admin')->group(function () {
    // Public Auth Routes
    Route::post('/login', [App\Http\Controllers\Api\Admin\AuthController::class, 'login']);
    
    // Protected Admin Routes
    Route::middleware('auth:api-admin')->group(function () {
        // Auth
        Route::post('/logout', [App\Http\Controllers\Api\Admin\AuthController::class, 'logout']);
        Route::post('/refresh', [App\Http\Controllers\Api\Admin\AuthController::class, 'refresh']);
        Route::get('/me', [App\Http\Controllers\Api\Admin\AuthController::class, 'me']);

        // Dashboard & Analytics
        Route::get('/dashboard', [App\Http\Controllers\Api\Admin\DashboardController::class, 'index']);
        Route::get('/analytics/dashboard', [App\Http\Controllers\Api\Admin\AnalyticsController::class, 'dashboard']);
        Route::get('/analytics/revenue', [App\Http\Controllers\Api\Admin\AnalyticsController::class, 'revenue']);
        Route::get('/analytics/users', [App\Http\Controllers\Api\Admin\AnalyticsController::class, 'users']);

        // User Management
        Route::apiResource('users', App\Http\Controllers\Api\Admin\UserController::class);

        // Core Content
        Route::apiResource('categories', App\Http\Controllers\Api\Admin\CategoryController::class);
        Route::apiResource('courses', App\Http\Controllers\Api\Admin\CourseController::class);
        Route::apiResource('subjects', App\Http\Controllers\Api\Admin\SubjectController::class);

        // Subject Specific Content (Notes, Papers, Videos)
        Route::get('/subjects/{subject}/notes', [App\Http\Controllers\Api\Admin\NoteController::class, 'index']);
        Route::post('/subjects/{subject}/notes', [App\Http\Controllers\Api\Admin\NoteController::class, 'store']);
        Route::patch('/notes/{id}/toggle-free', [App\Http\Controllers\Api\Admin\NoteController::class, 'toggleFree']);
        Route::apiResource('notes', App\Http\Controllers\Api\Admin\NoteController::class)->only(['update', 'destroy']);

        Route::get('/subjects/{subject}/papers', [App\Http\Controllers\Api\Admin\PaperController::class, 'index']);
        Route::post('/subjects/{subject}/papers', [App\Http\Controllers\Api\Admin\PaperController::class, 'store']);
        Route::patch('/papers/{id}/toggle-free', [App\Http\Controllers\Api\Admin\PaperController::class, 'toggleFree']);
        Route::apiResource('papers', App\Http\Controllers\Api\Admin\PaperController::class)->only(['update', 'destroy']);

        Route::get('/subjects/{subject}/videos', [App\Http\Controllers\Api\Admin\VideoController::class, 'index']);
        Route::post('/subjects/{subject}/videos', [App\Http\Controllers\Api\Admin\VideoController::class, 'store']);
        Route::patch('/videos/{id}/toggle-free', [App\Http\Controllers\Api\Admin\VideoController::class, 'toggleFree']);
        Route::apiResource('videos', App\Http\Controllers\Api\Admin\VideoController::class)->only(['update', 'destroy']);

        // Quiz Builder
        Route::apiResource('quizzes', App\Http\Controllers\Api\Admin\QuizController::class);
        Route::get('/quizzes/{quiz}/questions', [App\Http\Controllers\Api\Admin\QuizQuestionController::class, 'index']);
        Route::post('/quizzes/{quiz}/questions', [App\Http\Controllers\Api\Admin\QuizQuestionController::class, 'store']);
        Route::apiResource('questions', App\Http\Controllers\Api\Admin\QuizQuestionController::class)->only(['update', 'destroy']);

        // Orders
        Route::apiResource('orders', App\Http\Controllers\Api\Admin\OrderController::class)->only(['index', 'show']);

        // Settings
        Route::get('/settings', [App\Http\Controllers\Api\Admin\SettingsController::class, 'index']);
        Route::put('/settings', [App\Http\Controllers\Api\Admin\SettingsController::class, 'update']);
    });
});
