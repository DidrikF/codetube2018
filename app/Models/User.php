<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Relationships
    public function channel()
    {
        return $this->hasMany(Channel::class);
    }

    public function videos()
    {
        return $this->hasManyThrough(Video::class, Channel::class);
    }

    public function subscriptions() //A user has many subscirptions
    {
        return $this->hasMany(Subscription::class);
    }

    //OTHER METHODS
    public function subscribedChannels() //returns a list of channels the user is subscribed to
    {
        return $this->belongsToMany(Channel::class, 'subscriptions'); //a user belongs to many channels through the subscriptions table
    }

    public function isSubscribedTo(Channel $channel)
    {
        return (bool) $this->subscriptions->where('channel_id', $channel->id)->count();
    }

    public function ownsChannel(Channel $channel)
    {
        return (bool) $this->channel->where('id', $channel->id)->count(); 
        //if the user has a channel where the id is equal to the channel->id passed from the fornt end, he ownes the channel
    }
}
