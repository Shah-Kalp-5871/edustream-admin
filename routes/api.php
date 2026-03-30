<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentAuthController;
use App\Http\Controllers\Api\ContentApiController;
use App\Http\Controllers\Api\VideoStreamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/debug-ping', function() {
    return response()->json(['ping' => 'pong']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [StudentAuthController::class, 'register']);
    Route::post('/login', [StudentAuthController::class, 'login']);
    Route::post('/send-otp', [StudentAuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [StudentAuthController::class, 'verifyOtp']);
});

Route::group(['prefix' => 'public'], function () {
    Route::get('/courses', [ContentApiController::class, 'allCourses']);
    Route::get('/app-version', [ContentApiController::class, 'getAppVersion']);
});

// Protected Routes (Student JWT)
Route::group(['middleware' => 'auth:api-student'], function () {
    
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/me', [StudentAuthController::class, 'me']);
        Route::post('/logout', [StudentAuthController::class, 'logout']);
        Route::post('/refresh', [StudentAuthController::class, 'refresh']);
    });

    Route::group(['prefix' => 'content'], function () {
        Route::get('/home', [ContentApiController::class, 'home']);
        Route::get('/categories', [ContentApiController::class, 'categories']);
        Route::get('/categories/{id}/courses', [ContentApiController::class, 'categoryCourses']);
        Route::get('/courses/{id}/subjects', [ContentApiController::class, 'courseSubjects']);
        
        Route::get('/subjects/{id}', [ContentApiController::class, 'subjectDetails']);
        Route::get('/subjects/{id}/notes', [ContentApiController::class, 'subjectNotes']);
        Route::get('/subjects/{id}/videos', [ContentApiController::class, 'subjectVideos']);
        Route::get('/subjects/{id}/papers', [ContentApiController::class, 'subjectPapers']);
        Route::get('/subjects/{id}/quizzes', [ContentApiController::class, 'subjectQuizzes']);
        Route::get('/quiz-hub', [ContentApiController::class, 'quizHub']);
    });

    Route::group(['prefix' => 'learning'], function () {
        Route::get('/my-courses', [ContentApiController::class, 'myCourses']);
    });

    Route::group(['prefix' => 'quiz'], function () {
        Route::get('/{id}', [ContentApiController::class, 'quizDetails']);
        Route::post('/{id}/submit', [ContentApiController::class, 'submitQuiz']);
    });

    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', [ContentApiController::class, 'getCart']);
        Route::post('/add', [ContentApiController::class, 'addToCart']);
        Route::delete('/remove/{id}', [ContentApiController::class, 'removeFromCart']);
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::post('/initiate-payment', [ContentApiController::class, 'initiateRazorpayOrder']);
        Route::post('/verify-payment', [ContentApiController::class, 'verifyRazorpayPayment']);
        Route::get('/history', [ContentApiController::class, 'orderHistory']);
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::put('/', [StudentAuthController::class, 'updateProfile']);
    });

    // Video Streaming
    Route::get('/video/{id}/stream', [VideoStreamController::class, 'getStreamUrl']);
});

// Private Signed HLS Routes
Route::get('/video/stream/hls/{id}/playlist.m3u8', [VideoStreamController::class, 'streamHls'])
    ->name('video.stream.hls')
    ->middleware('signed');

Route::get('/video/stream/hls/{id}/{segment}', [VideoStreamController::class, 'streamSegment'])
    ->name('video.stream.segment');

// Razorpay Webhook
Route::post('/webhooks/razorpay', [\App\Http\Controllers\Api\RazorpayWebhookController::class, 'handle']);
