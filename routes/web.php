<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MoodResultController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','is_admin'])->group(function (){
    Route::get('/admin', function (){
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/users', function (){
        return view('admin.users');
    })->name('admin.user');
    
    Route::get('/admin/moods', function (){
        return view('admin.moods');
    })->name('admin.moods');

});

require __DIR__.'/auth.php';
