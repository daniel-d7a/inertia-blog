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
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render("Posts/Index", [
            'responseData' => [
                'posts' => PostService::getLatestPaginted($request->query()),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return Inertia::render("Posts/SinglePost", [
            'responseData' => [
                'post' => PostService::getWithVotes($post),
                'comments' => CommentService::getPostComments($post->id),
            ]
        ]);
    }

    /**
     * Show the page to create a new resource.
     */
    public function create()
    {
        return Inertia::render("Posts/Create", [
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

        // TODO: find a better way to do this
        $requestData = $request->validated();

        $newPost = Post::create([
            'title' => $requestData['title'],
            'body' => $requestData['body'],
            'user_id' => Auth::id(),
        ]);

        $existingTagsFromDb = Tag::whereIn('id', array_map(fn($tag) => $tag['id'], $requestData['existingTags']))->get();
        $newPost->tags()->attach($existingTagsFromDb);

        $newTags = $requestData['newTags'];

        $newTagsFromDb = [];
        foreach ($newTags as $tagName) {
            $newTagsFromDb[] = Tag::create(['name' => $tagName]);
        }

        $newPost->tags()->attach($newTagsFromDb);

        return to_route('posts.index');
    }

    /**
     * Show the page to edit a resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render("Posts/Update", [
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

        return redirect("posts/{$post->id}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('posts.index');
    }

}
