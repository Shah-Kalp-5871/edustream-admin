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
use App\Http\Controllers\BannerWebController;
use App\Http\Controllers\LegalController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/coming-soon', function () {
    return view('coming-soon');
});

Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
});

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
});

Route::get('/refund-policy', function () {
    return view('legal.refund-policy');
});

Route::get('/delete-account', [LegalController::class, 'deleteAccount'])->name('delete-account');
Route::post('/delete-account/send-otp', [LegalController::class, 'requestDeletionOtp'])->name('delete-account.send-otp');
Route::post('/delete-account', [LegalController::class, 'storeDeletionRequest'])->name('delete-account.request');

// Auth Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/export', [DashboardController::class, 'exportReport'])->name('dashboard.export');

// Category Routes
Route::get('/content/categories', [CategoryController::class, 'index']);
Route::get('/content/categories/create', [CategoryController::class, 'create']);
Route::post('/content/categories', [CategoryController::class, 'store']);
Route::get('/content/categories/{id}/edit', [CategoryController::class, 'edit']);
Route::put('/content/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/content/categories/{id}', [CategoryController::class, 'destroy']);

// Content Routes
Route::get('/content', [ContentController::class, 'index']);
Route::get('/content/course/create', [ContentController::class, 'create']);
Route::post('/content/course', [ContentController::class, 'store']);
Route::get('/content/course/{id}/edit', [ContentController::class, 'edit']);
Route::put('/content/course/{id}', [ContentController::class, 'update']);
Route::delete('/content/course/{id}', [ContentController::class, 'destroy']);
Route::get('/content/course/{id}', [ContentController::class, 'courseSubjects']);
// Subject Routes
Route::get('/content/course/{id}/subject/create', [ContentController::class, 'createSubject']);
Route::post('/content/course/{id}/subject', [ContentController::class, 'storeSubject']);
Route::get('/content/subject/{id}/edit', [ContentController::class, 'editSubject']);
Route::put('/content/subject/{id}', [ContentController::class, 'updateSubject']);
Route::delete('/content/subject/{id}', [ContentController::class, 'destroySubject']);
Route::get('/content/subject/{id}', [ContentController::class, 'subjectContent']);
Route::get('/content/notes/{id}', [ContentController::class, 'manageNotes']);
Route::post('/content/notes/{id}/folder', [ContentController::class, 'storeNoteFolder']);
Route::delete('/content/notes/folder/{id}', [ContentController::class, 'deleteNoteFolder']);
Route::post('/content/notes/{id}/upload', [ContentController::class, 'storeNote']);
Route::delete('/content/notes/file/{id}', [ContentController::class, 'deleteNote']);
Route::post('/content/notes/folder/{id}/update', [ContentController::class, 'updateNoteFolder']);
Route::post('/content/notes/file/{id}/update', [ContentController::class, 'updateNote']);
Route::post('/content/notes/file/{id}/toggle-free', [ContentController::class, 'toggleNoteFree']);
Route::post('/content/notes/reorder', [ContentController::class, 'reorderNotes']);

Route::get('/content/videos/{id}', [ContentController::class, 'manageVideos']);
Route::post('/content/videos/{id}/folder', [ContentController::class, 'storeVideoFolder']);
Route::delete('/content/videos/folder/{id}', [ContentController::class, 'deleteVideoFolder']);
Route::post('/content/videos/{id}/upload', [ContentController::class, 'storeVideo']);
Route::delete('/content/videos/file/{id}', [ContentController::class, 'deleteVideo']);
Route::post('/content/videos/folder/{id}/update', [ContentController::class, 'updateVideoFolder']);
Route::post('/content/videos/file/{id}/update', [ContentController::class, 'updateVideo']);
Route::post('/content/videos/file/{id}/toggle-free', [ContentController::class, 'toggleVideoFree']);
Route::post('/content/videos/reorder', [ContentController::class, 'reorderVideos']);

Route::get('/content/qa-papers/{id}', [ContentController::class, 'manageQAPapers']);
Route::post('/content/qa-papers/{id}/folder', [ContentController::class, 'storeQAPaperFolder']);
Route::delete('/content/qa-papers/folder/{id}', [ContentController::class, 'deleteQAPaperFolder']);
Route::post('/content/qa-papers/{id}/upload', [ContentController::class, 'storeQAPaper']);
Route::delete('/content/qa-papers/file/{id}', [ContentController::class, 'deleteQAPaper']);
Route::post('/content/qa-papers/folder/{id}/update', [ContentController::class, 'updateQAPaperFolder']);
Route::post('/content/qa-papers/file/{id}/update', [ContentController::class, 'updateQAPaper']);
Route::post('/content/qa-papers/file/{id}/toggle-free', [ContentController::class, 'toggleQAPaperFree']);

Route::get('/content/quiz/{id}', [ContentController::class, 'manageQuiz']);
Route::get('/content/quiz/{id}/builder', [ContentController::class, 'quizBuilder']);


// Quiz Routes
Route::get('/content/quiz/{id}', [ContentController::class, 'manageQuiz']);
Route::post('/content/quiz/{id}/store', [QuizController::class, 'store']);
Route::get('/content/quiz/{id}/manage', [QuizController::class, 'manage']);
Route::post('/quiz/toggle-status/{id}', [QuizController::class, 'toggleStatus']);
Route::post('/quiz/toggle-free/{id}', [QuizController::class, 'toggleFree']);
Route::post('/quiz/{id}/update', [QuizController::class, 'update']);
Route::delete('/quiz/{id}', [QuizController::class, 'destroy']);
Route::post('/quiz/{id}/question/store', [QuizController::class, 'storeQuestion']);
Route::post('/quiz/{id}/bulk-save', [QuizController::class, 'bulkSave']);
Route::post('/quiz/{id}/import-json', [QuizController::class, 'importJson']);
Route::post('/quiz/question/{id}/update', [QuizController::class, 'updateQuestion']);
Route::delete('/quiz/question/{id}', [QuizController::class, 'deleteQuestion']);
Route::post('/quiz/question/{id}/option/store', [QuizController::class, 'storeOption']);
Route::post('/quiz/option/{id}/update', [QuizController::class, 'updateOption']);
Route::delete('/quiz/option/{id}', [QuizController::class, 'deleteOption']);

// User Routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/edit', [UserController::class, 'edit']);

// Order Routes
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice']);

// Analytics & Settings
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');

    // Banner Routes
    Route::get('/banners', [BannerWebController::class, 'index']);
    Route::post('/banners', [BannerWebController::class, 'store']);
    Route::patch('/banners/{banner}/toggle', [BannerWebController::class, 'toggleStatus']);
    Route::delete('/banners/{banner}', [BannerWebController::class, 'destroy']);

    // Account Deletion Requests (Admin)
    Route::get('/admin/deletion-requests', [LegalController::class, 'adminDeletionRequests'])->name('admin.deletion-requests');
    Route::post('/admin/deletion-requests/{id}/status', [LegalController::class, 'updateDeletionStatus'])->name('admin.deletion-requests.update-status');
});
