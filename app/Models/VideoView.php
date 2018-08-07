<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoView extends Model
{
    protected $fillable = [
    	'user_id',
    	'ip'
    ];

    public function scopeByUser($query, User $user) //this method seems over the top, look at scopeLatestByIp()
    {
    	return $query->where('user_id', $user->id);
    }

    public function scopeLatestByUser($query, User $user)
    {
    	return $query->byUser($user)->orderBy('created_at', 'desc')->take(1);
    }

    public function scopeLatestByIp($query, $ip) //HOW does laravel know that the value passed in is the ip (the second property) and where does it get query from, it must be globally available
    {
    	return $query->where('ip', $ip)->orderBy('created_at', 'desc')->take(1);
    }
}
