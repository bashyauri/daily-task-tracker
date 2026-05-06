<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
    Route::get('/forgot-password', [PasswordResetController::class, 'showPasswordResetRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetEmail'])
        ->middleware('throttle:password-reset-requests')->name('password.email');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('throttle:password-reset')->name('password.store');

});
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'index'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed', 'throttle:10,1']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.send')
        ->middleware('throttle:6,1');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showPasswordResetForm'])
        ->name('password.reset');
});
Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('categories', CategoryController::class)->except(['show'])
        ->middlewareFor(['edit', 'update', 'destroy'], 'can:manage,category');
    Route::resource('tasks', TaskController::class)->except(['show'])
        ->middlewareFor(['index'], 'can:viewAny,'.Task::class)
        ->middlewareFor(['create', 'store'], 'can:create,'.Task::class)
        ->middlewareFor(['edit', 'update'], 'can:update,task')
        ->middlewareFor(['destroy'], 'can:delete,task');
    Route::patch('/tasks/{task}/toggle-completion', [TaskController::class, 'toggleCompletion'])
        ->name('tasks.toggle-completion')
        ->middleware('can:toggleCompletion,task');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::redirect('/', '/dashboard');
});
