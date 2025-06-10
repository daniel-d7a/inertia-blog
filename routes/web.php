<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');
Route::inertia('/about', 'About')->name('about');
Route::inertia('dashboard', "Dashboard")->middleware(['auth', 'verified'])->name('dashboard');


Route::get("/blog", [PostController::class, "index"])->name('blog.index');

Route::middleware('auth')->group(function () {

    // views
    Route::get("/blog/create", [PostController::class, "create"])->name('blog.create');
    Route::get("/blog/{post:slug}/edit", [PostController::class, "edit"])->name('blog.edit')->can('update', 'post');
    Route::get("/blog/{post:slug}", [PostController::class, "show"])->name('blog.show');

    // post actions
    Route::post("/blog", [PostController::class, "store"])->name('blog.store');
    Route::delete("/blog/{post:slug}", [PostController::class, "destroy"])->can('delete', 'post')->name('blog.destroy');
    Route::patch("/blog/{post:slug}", [PostController::class, "update"])->can('update', 'post')->name('blog.update');

    // comment actions
    Route::post("/blog/{post:slug}/comments", [CommentController::class, "store"])->name('comment.store');
    Route::delete('/blog/comments/{comment}', [CommentController::class, 'destroy'])->can("delete", "comment")->name("comment.delete");

    // vote actions
    Route::post('/blog/{post:slug}/vote', [VoteController::class, 'votePost'])->name('blog.vote');
    Route::post('/blog/{post:slug}/comments/{comment}/vote', [VoteController::class, 'voteComment'])->name('comment.vote');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
