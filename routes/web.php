<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth:sanctum', 'verified', 'role:user'])->get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

// لوحة التحكم: إما للمشرف أو للمستخدم العادي
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::check() && Auth::user()->hasRole('user')) {
            return redirect()->route('user.dashboard');
        } else {
            abort(403, 'Unauthorized');
        }
    })->name('dashboard');
});



// Routes للمشرف فقط
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/jobs', JobController::class);
    Route::resource('/applications', ApplicationController::class);
    Route::resource('/candidates', CandidateController::class);
    Route::get('/application/send-to-chatgpt', [ApplicationController::class , 'sendToGoogleAI']) ;
});

// Routes للمستخدم فقط
Route::middleware(['auth', 'role:user,admin'])->group(function () {
    Route::get('/candidates/create', [CandidateController::class, 'create'])->name('candidates.create');
    Route::get('/jobs/show/{id}', [JobController::class, 'show'])->name('jobs.show');
    Route::get('/applications/show/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/candidates/store', [CandidateController::class, 'store'])->name('candidates.store');
    Route::get('/jobs-by-category/{category}',[JobController::class , 'getJobsByCategory']);
    Route::get('/application/send-to-chatgpt', [ApplicationController::class , 'sendToGoogleAI']) ;
});
