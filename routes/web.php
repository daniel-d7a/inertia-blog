<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get("/posts", [PostController::class, "index"])->name('posts.index');

Route::middleware('auth')->group(function () {

    // views
    Route::get("/posts/create", [PostController::class, "create"]);
    Route::get("/posts/{post}/edit", [PostController::class, "edit"])->can('update', 'post');
    Route::get("/posts/{post}", [PostController::class, "show"]);

    // post actions
    Route::post("/posts", [PostController::class, "store"]);
    Route::delete("/posts/{post}", [PostController::class, "destroy"])->can('delete', 'post');
    Route::patch("/posts/{post}", [PostController::class, "update"])->can('update', 'post');

    // comment actions
    Route::post("/posts/{post}/comments", [CommentController::class, "store"]);

    // vote actions
    Route::post('/posts/{post}/vote', [VoteController::class, 'votePost']);
    Route::post('/posts/{post}/comments/{comment}/vote', [VoteController::class, 'voteComment']);
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
