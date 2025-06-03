<?php

namespace App\Services;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostService
{

    public static function getLatestPaginted(?array $search)
    {
        $q = $search['q'] ?? null;
        $tag = $search['tag'] ?? null;

        return static::_get()
            ->when(isset($q), function (Builder $query) use ($q) {
                $query
                    ->where('title', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            })
            ->when(isset($tag), function (Builder $query) use ($tag) {
                $query->whereHas('tags', function (Builder $query) use ($tag) {
                    $query->where('name', '=', $tag);
                });

                // $query->with([
                //     'tags' => function (Builder $innerQuery) use ($tag) {
                //         $innerQuery->where('name', '=', $tag);
                //     }
                // ]);
            })->paginate(10);
    }
    public static function getLatest()
    {
        return static::_get()->get();
    }


    private static function _get()
    {
        return Post::latest()
            // get the current user's vote on each post
            ->with([
                'votes' => function (Builder $query) {
                    $query->where('user_id', '=', Auth::id());
                }
            ])
            // map from 'post_votes.0.vote_type' to 'vote_type'
            // ->map(function (Post $post) {

            //     $postArray = $post->toArray();

            //     return array_merge(
            //         Arr::except($postArray, 'post_votes'),
            //         ['vote_type' => Arr::get($postArray, 'post_votes.0.vote_type')]
            //     );
            // })
        ;
    }


    public static function getWithVotes(Post $post)
    {
        // $vote = $post
        //     ->votes()
        //     ->whereUserId(Auth::id())
        //     ->get('vote_type')
        //     ->first()
        //         ?->toArray() ?? [];


        return $post->withCurrentUserVote();
    }

}