<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventSilentlyDiscardingAttributes($this->app->isLocal());
        Relation::morphMap([
            'Post' => Post::class,
            'Comment' => Comment::class,
        ]);

        Post::observe(PostObserver::class);
    }
}
