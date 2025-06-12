<?php

namespace App\Http\Controllers;

use App\Enums\VoteableType;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoteRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\VoteService;
use App\Traits\Voteable;


class VoteController extends Controller
{

    public function update(VoteRequest $request)
    {
        $data = $request->validated();
        $model = $this->resolveModel($data['voteable_type'], $data['voteable_id']);

        if (!$model) {
            return abort(404, "Voteable model not found");
        }
        VoteService::vote($model, $data['vote_type']);
        return redirect()->to($data['redirect_to']);
    }

    private function resolveModel(string $type, int $id)
    {
        return match ($type) {
            VoteableType::POST->value => Post::find($id),
            VoteableType::COMMENT->value => Comment::find($id),
            default => null,
        };
    }
}
