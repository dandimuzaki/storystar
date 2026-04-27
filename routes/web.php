<?php

use App\Http\Controllers\ClapController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/@{user:username}', [PublicProfileController::class,'show'])
    ->name('public_profile.show');

Route::get('/', [PostController::class, 'index'])
    ->name('dashboard');

Route::get('/posts', [PostController::class, 'index'])
    ->name('post.byCategory');

Route::get('/@{username}/{post:slug}', [PostController::class,'show'])
    ->name('post.show');

Route::get('/result', [SearchController::class,'search'])
    ->name('result');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/post/create', [PostController::class, 'create'])
        ->name('post.create');

    Route::post('/post/create', [PostController::class, 'store'])
        ->name('post.store');

    Route::get('/post/{post:slug}', [PostController::class, 'edit'])
        ->name('post.edit');

    Route::put('/post/{post:slug}', [PostController::class, 'update'])
        ->name('post.update');

    Route::post('/follow/{user:id}', [FollowerController::class, 'followUnfollow'])
        ->name('follow');

    Route::post('/clap/{post:id}', [ClapController::class, 'clap'])
        ->name('clap');

    Route::get('/@{username}', [PostController::class, 'show'])
        ->name('mypost');
    
    Route::delete('/post/{post:slug}', [PostController::class, 'destroy'])
        ->name('post.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
