<?php

namespace App\Services;

use App\Models\Comment;


class CommentService
{

    public static function getPostComments(int $postId)
    {
        return Comment::latest()->wherePostId($postId)->get();
    }

}