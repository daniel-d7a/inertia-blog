<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLink;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {

        $links = UserLink::where('user_id', auth()->id())->get();
        $followersCount = auth()->user()->followers()->count();
        $followingCount = auth()->user()->following()->count();
        $tags = auth()->user()->interests()->get();
        $postCounts = auth()->user()->posts()->count();
        $commentCount = auth()->user()->comments()->count();
        $voteCount = auth()->user()->votes()->count();

        // Logic to show the user's profile
        return inertia('UserProfile/Index', [
            "responseData" => [
                "links" => $links,
                "followersCount" => $followersCount,
                "followingCount" => $followingCount,
                "tags" => $tags,
                "postCounts" => $postCounts,
                "commentCount" => $commentCount,
                "voteCount" => $voteCount,
                "userId" => $user->id,
            ]
        ]);
    }

    public function updatePassword(Request $request, $user)
    {
        // Logic to update the user's password
        // Validate and update password logic here
    }

    public function settings(Request $request, $user)
    {
        // Logic to show user settings
        return inertia('Profile/Settings', ['user' => $user]);
    }

    public function updateSettings(Request $request, $user)
    {
        // Logic to update user settings
        // Validate and update settings logic here
    }

}
