<?php

namespace Database\Seeders;

use App\Enums\VoteableType;
use App\Enums\VoteType;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // create a user account
        $eyad = User::factory()->create([
            'name' => 'eyad',
            'email' => 'eyad@eyad.com',
            'password' => bcrypt('password'),
        ]);

        // create 5 tags
        $tags = Tag::factory(5)->create();

        // create 5 users
        $users = User::factory(5)->create();

        // create 15 posts attched to existing users
        $posts = Post::factory(15)->recycle($users)->create();

        // attach 2 random tags to each post
        $posts->each(function (Post $post) use ($tags) {
            $post->tags()->attach($tags->random(2));
        });

        // create 15 comments attached to existing users and posts
        $comments = Comment::factory(50)->recycle($users)->recycle($posts)->create();

        // Create 300 votes distributed between posts and comments
        $voteables = collect()
            ->merge($posts->map(fn($post) => ['type' => 'post', 'id' => $post->id, 'model' => $post]))
            ->merge($comments->map(fn($comment) => ['type' => 'comment', 'id' => $comment->id, 'model' => $comment]));


        $this->createRandomVotes($users, $voteables);

        $this->createRandomVotes(collect([$eyad]), $voteables, 100, 50);

    }

    private function createOneRandomVote($user_id, $voteable)
    {
        Vote::create([
            'user_id' => $user_id,
            'vote_type' => rand(0, 1) ? VoteType::UP->value : VoteType::DOWN->value,
            'voteable_id' => $voteable['id'],
            'voteable_type' => $voteable['type'] === 'post'
                ? VoteableType::POST->value
                : VoteableType::COMMENT->value,
        ]);

    }

    private function createRandomVotes($users, $voteables, $maxAttempts = 400, $maxVotes = 200)
    {
        $createdVotes = 0;
        $attemptNum = 0; // Prevent infinite loops
        $usedCombinations = [];

        while ($createdVotes < $maxVotes && $attemptNum++ < $maxAttempts) {
            $voteable = $voteables->random();
            $userId = $users->random()->id;

            $combinationKey = "{$userId}:{$voteable['id']}:{$voteable['type']}";

            if (!isset($usedCombinations[$combinationKey])) {
                try {
                    $this->createOneRandomVote($userId, $voteable);
                    $usedCombinations[$combinationKey] = true;
                    $createdVotes++;
                } catch (\Illuminate\Database\QueryException $e) {
                    // Duplicate found, try again
                    continue;
                }
            }
        }
        $this->command->info("Created {$createdVotes} unique votes (some duplicates skipped)");

    }
}
