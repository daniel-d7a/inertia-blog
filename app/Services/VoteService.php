<?php

namespace App\Services;

use App\Traits\Voteable;
use Illuminate\Support\Facades\Auth;

class VoteService
{
    public static function vote($model, $vote_type)
    {
        $prevVote = $model->getCurrentUserVote();

        if ($prevVote) {
            // same vote type, remove the vote
            if ($prevVote->vote_type === $vote_type) {
                $prevVote->delete();
            }
            // different vote type, switch vote
            else {
                $prevVote->update(['vote_type' => $vote_type]);
            }

        } else {
            $model->votes()->create(['user_id' => Auth::id(), 'vote_type' => $vote_type]);
        }
        $model->refresh();

    }

}