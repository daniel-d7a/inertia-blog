<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');
Route::inertia('/about', 'About')->name('about');
Route::inertia('dashboard', "Dashboard")->middleware(['auth', 'verified'])->name('dashboard');




Route::prefix("profile")->group(function () {
    Route::get("{user}", []);

    Route::middleware('auth')->group(function () {

        Route::get("{user}/update-password", []);
        Route::patch("{user}/update-password", []);

        Route::get("{user}/settings", []);
        Route::patch("{user}/settings", []);
    });

});


Route::prefix("blog")->group(function () {

    Route::get("", [PostController::class, "index"])->name('blog.index');
    Route::get("{post:slug}", [PostController::class, "show"])->name('blog.show');

    Route::middleware('auth')->group(function () {

        Route::get("create", [PostController::class, "create"])->name('blog.create');
        Route::post("", [PostController::class, "store"])->name('blog.store');
        Route::get("{post:slug}/edit", [PostController::class, "edit"])->name('blog.edit')->can('update', 'post');
        Route::patch("{post:slug}", [PostController::class, "update"])->can('update', 'post')->name('blog.update');
        Route::delete("{post:slug}", [PostController::class, "destroy"])->can('delete', 'post')->name('blog.destroy');
        Route::post("{post:slug}/comments", [CommentController::class, "store"])->name('comment.store');

        Route::prefix("blog/comments")->group(function () {

            Route::patch("{comment}", [CommentController::class, "update"])->can("update", "comment")->name("comment.update");
            Route::delete('{comment}', [CommentController::class, 'destroy'])->can("delete", "comment")->name("comment.delete");

        });

        Route::post('/vote', [VoteController::class, 'update'])->name("vote.update");
    });

});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
