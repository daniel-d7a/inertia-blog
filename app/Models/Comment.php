<?php

namespace App\Models;

use App\Enums\VoteType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
    // implements Votable
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'votes_count'];
    protected $with = ['user'];

    #region relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(CommentVote::class);
    }
    #endregion

    public function vote(VoteType $voteType, int $voteAmount = 1)
    {
        if ($voteType == VoteType::UP) {
            $this->increment('votes_count', $voteAmount);
        } else {
            $this->decrement('votes_count', $voteAmount);
        }
    }

    public function unVote(VoteType $vote)
    {
        $voteInverse = $vote === VoteType::UP ? VoteType::DOWN : VoteType::UP;

        $this->vote($voteInverse);
    }
}
