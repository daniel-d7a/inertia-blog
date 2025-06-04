<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Services\VoteService;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function votePost(Request $request, Post $post)
    {

        VoteService::VotePost($post, $request->input("vote_type"));

        return redirect($request->input('redirect_to'));
    }

    public function voteComment(Request $request, Post $post, Comment $comment)
    {

        VoteService::VoteComment($comment, $request->input("vote_type"));

        return redirect("blog/{$post->slug}");
    }
}
