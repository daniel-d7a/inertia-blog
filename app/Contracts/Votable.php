<?php

namespace App\Contracts;

use App\Enums\VoteType;
use Illuminate\Database\Eloquent\Relations\HasMany;


// TODO: how to make this work ?????????
interface Votable
{
    public function votes(): HasMany;
    public function vote(VoteType $vote, int $amount = 1);
    public function unVote(VoteType $vote);
}
