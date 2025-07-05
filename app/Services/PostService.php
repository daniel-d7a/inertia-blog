<?php

namespace App\Services;

use App\Enums\VoteableType;
use App\Models\Vote;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PostService
{
    public static function getLatestPaginted(?array $search)
    {
        $q = $search['q'] ?? null;
        $tag = $search['tag'] ?? null;

        return static::baseQuery()
            ->latest()
            ->when(isset($q), static::applySearchFilter($q))
            ->when(isset($tag), static::applyTagFilter($tag))
            ->paginate(10);
    }

    public static function getOne(Post $post)
    {
        return static::baseQuery()->findOrFail($post->id);
    }

    public static function create(Request $request){
        return DB::transaction(function () use ($request) {
            $post = Post::create($request->safe()->only(['title', 'body']) + ['user_id' => Auth::id()]);

            static::syncTags($post, $request->validated());

            return $post;
        });
    }


    public static function update(Post $post, Request $request){
;
        $post->update($request->safe()->only(['title', 'body']));
        static::syncTags($post, $request->validated());
        return $post;
    }



    private static function baseQuery()
    {
        $query = Post::query()
            ->addSelect([
                'current_user_vote' => Vote::select('vote_type')
                    ->whereColumn('voteable_id', 'posts.id')
                    ->where('voteable_type', VoteableType::POST->value)
                    ->where('user_id', Auth::id())
                    ->take(1)
            ])
            // ->withSum('votes as votes', 'vote_type')
            ->selectRaw('(select sum(cast(vote_type as char)) from votes where voteable_id = posts.id and voteable_type = ?) as votes', [VoteableType::POST->value]);

        return $query;
    }

    protected static function applySearchFilter(?string $searchTerm): \Closure
    {
        return function (Builder $query) use ($searchTerm) {
            $query->where(function (Builder $query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('body', 'like', "%{$searchTerm}%");
            });
        };
    }

    protected static function applyTagFilter(?string $tagName): \Closure
    {
        return function (Builder $query) use ($tagName) {
            $query->whereHas('tags', function (Builder $query) use ($tagName) {
                $query->where('name', $tagName);
            });
        };
    }

    private static function syncTags(Post $post, array $validatedData): void
    {
        // Handle all tags in one go
        $tagIds = collect($validatedData['existingTags'] ?? [])->pluck('id');

        if (!empty($validatedData['newTags'])) {
            // Sync existing and new tags (preventing duplicates)
            $tagIds = $tagIds->merge(
                Tag::resolveTags($validatedData['newTags'])->pluck('id')
            );
        }

        $post->tags()->sync($tagIds);
    }

}