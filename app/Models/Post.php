<?php

namespace App\Models;

use App\Traits\Voteable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use Voteable;

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

    #endregion

    private static function createSlug(Post $post)
    {
        $slug = Str::slug($post->title);
        $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
        $post->slug = $count ? "{$slug}-{$count}" : $slug;
    }

}
