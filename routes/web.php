<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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

// Content Routes
Route::get('/content', [ContentController::class, 'index']);
Route::get('/content/course/{id}', [ContentController::class, 'courseSubjects']);
Route::get('/content/subject/{id}', [ContentController::class, 'subjectManage']);
Route::get('/content/notes/{id}', [ContentController::class, 'manageNotes']);
Route::get('/content/videos/{id}', [ContentController::class, 'manageVideos']);
Route::get('/content/quiz/{id}', [ContentController::class, 'manageQuiz']);
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


