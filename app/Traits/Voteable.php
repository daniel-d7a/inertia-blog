<?php

namespace App\Traits;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait Voteable
{

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function getCurrentUserVote(): ?Vote
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->votes()
            ->where('user_id', Auth::id())
            ->first();

    }
}
