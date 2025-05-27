<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Post;
use App\Models\PostVote;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create 5 tags
        $tags = Tag::factory(5)->create();

        // create 5 users
        $users = User::factory(5)->create();

        // create 15 posts attched to existing users
        $posts = Post::factory(15)->recycle($users)->create();


        // we have 15 posts and 5 users
        // max unique votes = 15 * 5 = 75
        // create 70 votes spread across posts and users
        // make sure a user can't vote on the same post more than once
        // TODO: find a more elegant way for this
        $postVoteCount = 0;
        while ($postVoteCount < 70) {
            $postVote = PostVote::factory()->recycle($posts)->recycle($users)->make();

            $exists = PostVote::wherePostId($postVote->post_id)->whereUserId($postVote->user_id)->exists();

            if (!$exists) {
                $postVote->save();
                $postVoteCount++;
            }
        }

        // $votes = PostVote::factory(70)
        //     ->recycle($posts)
        //     ->recycle($users)
        //     ->make()
        //     ->unique(function (PostVote $postVote) {
        //         return [$postVote->post_id, $postVote->user_id];
        //     })
        //     ->toArray();

        // dd($votes);

        // for each post attach 2 random tags and calculate the votes
        // TODO: find a more elegant way for this
        $posts->each(function (Post $post) use ($tags) {
            $post->tags()->attach($tags->random(2));

            $post->votes_count = PostVote::wherePostId($post->id)->get()->sum(fn(PostVote $vote) => (int) $vote->vote_type);

            $post->save();
        });

        // create 15 comments attached to existing users and posts
        $comments = Comment::factory(15)->recycle($users)->recycle($posts)->create();

        // we have 15 comments and 5 users
        // max unique votes = 15 * 5 = 75
        // create 70 votes spread across comments and users
        // make sure a user can't vote on the same comment more than once
        // TODO: find a more elegant way for this
        $commentVotesCount = 0;
        while ($commentVotesCount < 70) {
            $commentVote = CommentVote::factory()->recycle($comments)->recycle($users)->make();

            $exists = CommentVote::whereCommentId($commentVote->comment_id)->whereUserId($commentVote->user_id)->exists();

            if (!$exists) {
                $commentVote->save();
                $commentVotesCount++;
            }
        }

        Comment::each(function (Comment $comment) {
            $comment->votes_count = CommentVote::whereCommentId($comment->id)->get()->sum(fn(CommentVote $vote) => (int) $vote->vote_type);
            $comment->save();
        });


        /**
         * idea proposal
         * 
         * for each user
         *      for each comment
         *          create a random vote and save it to db
         * 
         */
    }
}
