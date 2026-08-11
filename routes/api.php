<?php

use App\Http\Controllers\API\AdminAPIController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\PaperController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\LibraryController;
use App\Http\Middleware\VerifyDeviceFingerprint;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware([VerifyDeviceFingerprint::class])->group(function () {
    
    // Public/Guest Session Initialization
    Route::post('/auth/guest-init', [AuthController::class, 'guestInit']);

    // Taxonomy metadata (accessible before auth)
    Route::get('/levels', [SubjectController::class, 'getLevels']);
    Route::get('/streams', [SubjectController::class, 'getStreams']);
    Route::get('/semesters', [SubjectController::class, 'getSemesters']);
    Route::get('/boards', [SubjectController::class, 'getBoards']);
    Route::get('/subjects', [SubjectController::class, 'getSubjects']);
    Route::get('/subjects/{id}/years', [SubjectController::class, 'getYears']);

    // Authenticated API Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // Auth Lifecycle
        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken']);
        Route::put('/users/{id}/onboarding', [AuthController::class, 'saveOnboarding']);
        Route::post('/users/{id}/subjects', [AuthController::class, 'syncUserSubjects']);

        // Home Dashboard
        Route::get('/home', [HomeController::class, 'getDashboard']);

        // Papers Access & Check
        Route::get('/papers', [PaperController::class, 'getPapers']);
        Route::post('/papers/{id}/access-check', [PaperController::class, 'checkAccessAndGetUrl']);
        
        // Crowdsourced submissions and requests
        Route::post('/submissions', [PaperController::class, 'submitPaper']);
        Route::post('/requests', [PaperController::class, 'requestPaper']);

        // Library & User collections (Saved, Personal requests, Submissions)
        Route::get('/papers/saved', [LibraryController::class, 'getSavedPapers']);
        Route::post('/papers/{id}/save', [LibraryController::class, 'toggleSavePaper']);
        Route::get('/my-requests', [LibraryController::class, 'getMyRequests']);
        Route::get('/my-submissions', [LibraryController::class, 'getMySubmissions']);

        // Admin Management Actions
        Route::get('/admin/stats', [AdminAPIController::class, 'stats']);
        Route::post('/boards', [AdminAPIController::class, 'createBoard']);
        Route::post('/subjects', [AdminAPIController::class, 'createSubject']);
        Route::delete('/subjects/{id}', [AdminAPIController::class, 'deleteSubject']);
        Route::post('/papers', [AdminAPIController::class, 'createPaper']);
        Route::get('/submissions', [AdminAPIController::class, 'getSubmissions']);
        Route::patch('/submissions/{id}/approve', [AdminAPIController::class, 'approveSubmission']);
        Route::patch('/submissions/{id}/reject', [AdminAPIController::class, 'rejectSubmission']);
    });

    // Public Admin Login Route under Device verification
    Route::post('/admin/login', [AdminAPIController::class, 'login']);
});

// Secure signed route for paper download (needs validation bypass from global signature checks as it relies on URL signatures)
Route::get('/papers/{id}/download', [PaperController::class, 'downloadPaper'])
    ->name('api.papers.download');
