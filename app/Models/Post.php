<?php

namespace App\Models;

use App\Enums\VoteType;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
    // implements Votable
{
    use HasFactory;

    // TODO: remove user id after adding auth
    // todo: why is votes a fillable property ? to count it properly in the seeder
    protected $fillable = ["title", "body", "user_id", 'votes_count'];

    protected $with = ['user', 'tags'];

    #region relations 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PostVote::class);
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

    public function withCurrentUserVote()
    {
        return PostService::getLatest()->where('id', '=', $this->id)->first();
    }

    // /**
    //  * Retrieve the model for a bound value.
    //  *
    //  * @param  mixed  $value
    //  * @param  string|null  $field
    //  * @return \Illuminate\Database\Eloquent\Model|null
    //  */
    // public function resolveRouteBinding($value, $field = null)
    // {
    //     return static::where('id', '=', $value)->first();
    // }
}
