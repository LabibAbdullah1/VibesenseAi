<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\DiaryAnalysisController;
use App\Http\Controllers\TestimonyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserDashboardController;

// ==================== PUBLIC ROUTES ====================
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/demo', function () {
    return view('landing.demo');
})->name('demo');

Route::get('/hero', [LandingController::class, 'hero'])->name('landing.hero');
Route::get('/features', [LandingController::class, 'features'])->name('landing.features');
Route::get('/cta', [LandingController::class, 'cta'])->name('landing.cta');
Route::get('/testimonials', [LandingController::class, 'testimonials'])->name('landing.testimonials');


Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        // Profile (ini bawaan Breeze)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Diary
        Route::get('/diary', [DiaryController::class, 'index'])->name('diary.index');
        Route::get('/diary/create', [DiaryController::class, 'create'])->name('diary.create');
        Route::post('/diary/store', [DiaryController::class, 'store'])->name('diary.store');
        Route::get('/diary/{id}', [DiaryController::class, 'show'])->name('diary.show');

        // Analysis
        Route::prefix('analysis')->name('analysis.')->group(function () {
            Route::get('/', [DiaryAnalysisController::class, 'index'])->name('index');
            Route::get('/{id}', [DiaryAnalysisController::class, 'show'])->name('show');
            Route::post('/regenerate', [DiaryAnalysisController::class, 'regenerate'])->name('regenerate');
        });

        // Settings (kalau ada)
        Route::get('/settings', function () {
            return view('settings.index');
        })->name('settings');

        // Testimony
        Route::prefix('testimony')->name('testimony.')->group(function () {
            Route::get('/', [TestimonyController::class, 'index'])->name('index');
            Route::get('/create', [TestimonyController::class, 'create'])->name('create');
            Route::post('/', [TestimonyController::class, 'store'])->name('store');
            Route::get('/{testimony}', [TestimonyController::class, 'show'])->name('show');
            Route::get('/{testimony}/edit', [TestimonyController::class, 'edit'])->name('edit');
            Route::put('/{testimony}', [TestimonyController::class, 'update'])->name('update');
            Route::delete('/{testimony}', [TestimonyController::class, 'destroy'])->name('destroy');
        });
    });


// ==================== ADMIN ROUTES ====================
// Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

//     // Dashboard
//     Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

//     // Admin Testimony Management (Read, Update, Delete only - no create)
//     Route::prefix('testimonies')->name('testimonies.')->group(function () {
//         Route::get('/', [TestimonyController::class, 'adminIndex'])->name('index');
//         Route::get('/{testimony}', [TestimonyController::class, 'adminShow'])->name('show');
//         Route::get('/{testimony}/edit', [TestimonyController::class, 'adminEdit'])->name('edit');
//         Route::put('/{testimony}', [TestimonyController::class, 'adminUpdate'])->name('update');
//         Route::delete('/{testimony}', [TestimonyController::class, 'adminDestroy'])->name('destroy');
//         Route::patch('/{testimony}/approve', [TestimonyController::class, 'approve'])->name('approve');
//         Route::patch('/{testimony}/reject', [TestimonyController::class, 'reject'])->name('reject');
//     });

//     // Admin bisa lihat semua diaries (optional)
//     Route::prefix('diaries')->name('diaries.')->group(function () {
//         Route::get('/', [DiaryController::class, 'adminIndex'])->name('index');
//         Route::get('/{diary}', [DiaryController::class, 'adminShow'])->name('show');
//     });

//     // Admin bisa lihat semua analyses (optional)
//     Route::prefix('analyses')->name('analyses.')->group(function () {
//         Route::get('/', [DiaryAnalysisController::class, 'adminIndex'])->name('index');
//         Route::get('/{analysis}', [DiaryAnalysisController::class, 'adminShow'])->name('show');
//     });
// });

require __DIR__ . '/auth.php';
