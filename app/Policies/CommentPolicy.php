<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\comment;
use App\Models\User;

class CommentPolicy
{

    public function before(User $user)
    {
        if ($user->isAdmin())
            return true;

        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, comment $comment): bool
    // {

    // }

    /**
     * Determine whether the user can create models.
     */
    // public function create(User $user): bool
    // {
    //     return Auth::check();
    // }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, comment $comment): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, comment $comment): bool
    // {
    //     //
    // }
}
