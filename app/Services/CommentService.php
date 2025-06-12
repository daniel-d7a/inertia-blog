<?php

namespace App\Services;

use App\Enums\VoteableType;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;


class CommentService
{

    public static function getPostComments(int $postId)
    {
        return Comment::query()
            ->where('post_id', $postId)
            ->addSelect([
                'current_user_vote' => Vote::select('vote_type')
                    ->whereColumn('voteable_id', 'comments.id')
                    ->where('voteable_type', VoteableType::COMMENT->value)
                    ->where('user_id', Auth::id())
                    ->take(1)
            ])
            ->selectRaw('(select sum(cast(vote_type as char)) from votes where voteable_id = comments.id and voteable_type = ?) as votes', [VoteableType::COMMENT->value])
            ->latest()
            ->get();
    }

}