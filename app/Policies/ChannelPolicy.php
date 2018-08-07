<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Channel;
use Illuminate\Auth\Access\HandlesAuthorization;

//remember to add to the auth service provider
class ChannelPolicy
{
    use HandlesAuthorization;

    public function edit(User $user, Channel $channel)
    {
        return $user->id == $channel->user_id;
    }

    public function update(User $user, Channel $channel)
    {
        return $user->id == $channel->user_id;
    }

    public function subscribe(User $user, Channel $channel)
    {
        if($user->isSubscribedTo($channel)) {
            return false;
        }

    	return !$user->ownsChannel($channel); //you have to not own the channel in order to subscribe
    }

    public function unsubscribe(User $user, Channel $channel)
    {
    	return $user->isSubscribedTo($channel);
    }

}
