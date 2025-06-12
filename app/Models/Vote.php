<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class Vote extends Model
{
    /** @use HasFactory<\Database\Factories\VoteFactory> */
    use HasFactory;

    protected $fillable = ["user_id", "vote_type"];

    protected $casts = ['vote_type' => 'integer'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }
}
