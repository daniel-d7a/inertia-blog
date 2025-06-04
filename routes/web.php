<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');
Route::inertia('/about', 'About');
Route::inertia('dashboard', "Dashboard")->middleware(['auth', 'verified'])->name('dashboard');


Route::get("/blog", [PostController::class, "index"])->name('blog.index');

Route::middleware('auth')->group(function () {

    // views
    Route::get("/blog/create", [PostController::class, "create"]);
    Route::get("/blog/{post:slug}/edit", [PostController::class, "edit"])->can('update', 'post');
    Route::get("/blog/{post:slug}", [PostController::class, "show"]);

    // post actions
    Route::post("/blog", [PostController::class, "store"]);
    Route::delete("/blog/{post:slug}", [PostController::class, "destroy"])->can('delete', 'post');
    Route::patch("/blog/{post:slug}", [PostController::class, "update"])->can('update', 'post');

    // comment actions
    Route::post("/blog/{post:slug}/comments", [CommentController::class, "store"]);

    // vote actions
    Route::post('/blog/{post:slug}/vote', [VoteController::class, 'votePost']);
    Route::post('/blog/{post:slug}/comments/{comment}/vote', [VoteController::class, 'voteComment']);
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
