<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

//remember to add to the auth service provider
class VideoPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Video $video)
    {
        return $user->id === $video->channel->user_id; //
    }

    public function edit(User $user, Video $video)
    {
        return $user->id === $video->channel->user_id; //the user_id accosiated with the channel the video belongs to, compared to the id of the logged in user
    } 

    public function delete(User $user, Video $video)
    {
        return $user->id === $video->channel->user_id; //the user_id accosiated with the channel the video belongs to, compared to the id of the logged in user
    }

    public function vote(User $user, Video $video)
    {
        if(!$video->canBeAccessed($user)) {
            return false;
        }
        if(!$video->votesAllowed()) {
            return false;
        }

        return true;

    }

    public function comment(User $user, Video $video)
    {
        if(!$video->canBeAccessed()) {
            return false;
        }

        if(!$video->commentsAllowed()) {
            return false;
        }

        return true;
    }
    
}
