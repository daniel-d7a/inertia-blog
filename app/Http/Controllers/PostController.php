<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Tag;
use App\Services\CommentService;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dump($request->query());
        $data = PostService::getLatestPaginted($request->query());

        return Inertia::render("Blog/Index", [
            'responseData' => [
                'posts' => $data,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return Inertia::render("Blog/SinglePost", [
            'responseData' => [
                'post' => PostService::getOne($post),
                'comments' => CommentService::getPostComments($post->id),
            ]
        ]);
    }

    /**
     * Show the page to create a new resource.
     */
    public function create()
    {
        return Inertia::render("Blog/Create", [
            'responseData' => [
                'tags' => Tag::all(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StorePostRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $post = Post::create([
                'title' => $request->validated('title'),
                'body' => $request->validated('body'),
                'user_id' => Auth::id()
            ]);

            // Handle all tags in one go
            $tagIds = collect($request->validated('existingTags'))->pluck('id');

            // Sync existing and new tags (preventing duplicates)
            $tagIds = $tagIds->merge(
                Tag::resolveTags($request->validated('newTags'))->pluck('id')
            );

            $post->tags()->sync($tagIds);

            return to_route('blog.index');
        });
    }

    /**
     * Show the page to edit a resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render("Blog/Update", [
            'responseData' => [
                'post' => $post,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return redirect("blog/{$post->slug}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('blog.index');
    }

}
