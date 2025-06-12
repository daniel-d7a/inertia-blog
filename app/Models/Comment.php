<?php

namespace App\Models;

use App\Enums\VoteType;
use App\Traits\Voteable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    use Voteable;

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
    #endregion
}
