<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/reset-password', [AuthController::class, 'resetPassword']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

// Category Routes
Route::get('/content/categories', [CategoryController::class, 'index']);
Route::get('/content/categories/create', [CategoryController::class, 'create']);
Route::get('/content/categories/{id}/edit', [CategoryController::class, 'edit']);

// Content Routes
Route::get('/content', [ContentController::class, 'index']);
Route::get('/content/course/create', [ContentController::class, 'create']);
Route::get('/content/course/{id}/edit', [ContentController::class, 'edit']);
Route::get('/content/course/{id}', [ContentController::class, 'courseSubjects']);
// Subject Routes
Route::get('/content/course/{id}/subject/create', [ContentController::class, 'createSubject']);
Route::post('/content/course/{id}/subject', [ContentController::class, 'storeSubject']);
Route::get('/content/subject/{id}/edit', [ContentController::class, 'editSubject']);
Route::put('/content/subject/{id}', [ContentController::class, 'updateSubject']);
Route::delete('/content/subject/{id}', [ContentController::class, 'destroySubject']);
Route::get('/content/subject/{id}', [ContentController::class, 'subjectContent']);
Route::get('/content/notes/{id}', [ContentController::class, 'manageNotes']);
Route::get('/content/videos/{id}', [ContentController::class, 'manageVideos']);
Route::get('/content/quiz/{id}', [ContentController::class, 'manageQuiz']);
Route::get('/content/quiz/{id}/builder', [ContentController::class, 'quizBuilder']);
Route::get('/content/qa-papers/{id}', [ContentController::class, 'manageQAPapers']);

// Quiz Routes
Route::get('/quizzes', [QuizController::class, 'index']);
Route::get('/quizzes/create', [QuizController::class, 'create']);
Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit']);
Route::get('/quizzes/{id}/questions', [QuizController::class, 'questions']);

// User Routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/edit', [UserController::class, 'edit']);

// Order Routes
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice']);

// Analytics & Settings
Route::get('/analytics', [AnalyticsController::class, 'index']);
Route::get('/settings', [SettingsController::class, 'index']);


