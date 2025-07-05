<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;



class PostObserver
{

    public function saving(Post $post)
    {
        $this->createSlug($post);
        $this->setTimeToRead($post); 
    }

    private function createSlug(Post $post)
    {
        $slug = Str::slug($post->title);
        $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
        $post->slug = $count ? "{$slug}-{$count}" : $slug;
    }

    private function setTimeToRead(Post $post)
    {
        $post->time_to_read = $this->calculateTimeToRead($post->body);
    }

    private function calculateTimeToRead(string $body): int
    {
        // Average reading speed (words per minute)
        $wordsPerMinute = 200;
        $wordCount = str_word_count(strip_tags($body));
        $minutes = ceil($wordCount / $wordsPerMinute);

        return (int) max(1, $minutes);
    }


}


