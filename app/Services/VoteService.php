<?php

namespace App\Services;

use App\Enums\VoteType;
use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Post;
use App\Models\PostVote;
use Illuminate\Support\Facades\Auth;

class VoteService
{

    public static function VotePost(Post $post, string $vote_type)
    {
        $voteType = VoteType::from($vote_type);
        $prevVote = PostVote::wherePostId($post->id)->whereUserId(Auth::id())->first();

        // if no vote and I vote add vote
        if (!$prevVote) {
            $post->votes()->create([
                'user_id' => Auth::id(),
                'vote_type' => $voteType->value,
            ]);
            $post->vote($voteType, 1);
        }
        // if vote and I vote different add double the vote
        else if ($prevVote->vote_type != $voteType->value) {
            $prevVote->update(['vote_type' => $voteType->value]);
            $post->vote($voteType, 2);
        }
        // if vote and I vote same remove vote
        else {
            $prevVote->delete();
            $post->unvote($voteType);
        }

        $post->refresh();
    }

    public static function VoteComment(Comment $comment, string $vote_type)
    {
        $voteType = VoteType::from($vote_type);
        $prevVote = CommentVote::whereCommentId($comment->id)->whereUserId(Auth::id())->first();

        // if no vote and I vote add vote
        if (!$prevVote) {
            $comment->votes()->create([
                'user_id' => Auth::id(),
                'vote_type' => $voteType->value,
            ]);
            $comment->vote($voteType, 1);
        }
        // if vote and I vote different add double the vote
        else if ($prevVote->vote_type != $voteType->value) {
            $prevVote->update(['vote_type' => $voteType->value]);
            $comment->vote($voteType, 2);
        }
        // if vote and I vote same remove vote
        else {
            $prevVote->delete();
            $comment->unVote($voteType);
        }

        $comment->refresh();
    }


    // public static function Vote(Votable $votable, string $vote_type)
    // {
    //     $voteType = VoteType::from($vote_type);
    //     $prevVote = $votable->votes()->where('user_id', '=', Auth::id())->first();

    //     // if no vote and I vote add vote
    //     if (!$prevVote) {
    //         $votable->votes()->create([
    //             'user_id' => Auth::id(),
    //             'vote_type' => $voteType->value,
    //         ]);
    //         $votable->vote($voteType);
    //     }
    //     // if vote and I vote different add double the vote
    //     else if ($prevVote->vote_type != $voteType->value) {
    //         $prevVote->update(['vote_type' => $voteType->value]);
    //         $votable->vote($voteType, 2);
    //     }
    //     // if vote and I vote same remove vote
    //     else {
    //         $prevVote->delete();
    //         $votable->unVote($voteType);
    //     }

    //     // todo: how correct is this ??????
    //     $votable->votes()->getParent()->refresh();
    // }
}