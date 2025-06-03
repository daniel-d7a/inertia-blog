<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::inertia('/', "Home")->name('home');
Route::inertia('dashboard', "Dashboard")->middleware(['auth', 'verified'])->name('dashboard');


Route::get("/blog", [PostController::class, "index"])->name('blog.index');

Route::middleware('auth')->group(function () {

    // views
    Route::get("/blog/create", [PostController::class, "create"]);
    Route::get("/blog/{post}/edit", [PostController::class, "edit"])->can('update', 'post');
    Route::get("/blog/{post}", [PostController::class, "show"]);

    // post actions
    Route::post("/blog", [PostController::class, "store"]);
    Route::delete("/blog/{post}", [PostController::class, "destroy"])->can('delete', 'post');
    Route::patch("/blog/{post}", [PostController::class, "update"])->can('update', 'post');

    // comment actions
    Route::post("/blog/{post}/comments", [CommentController::class, "store"]);

    // vote actions
    Route::post('/blog/{post}/vote', [VoteController::class, 'votePost']);
    Route::post('/blog/{post}/comments/{comment}/vote', [VoteController::class, 'voteComment']);
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
