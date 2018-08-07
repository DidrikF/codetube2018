<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

class Channel extends Model
{
    use Searchable;

    protected $fillable = [
    	'name',
    	'slug',
    	'description',
    	'image_filename',
    ];
    

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    //If you would like model binding to use a database column other than id when retrieving a given model class, you may override with this function
    //This is used for Route Model Binding
    public function getRouteKeyName()
    {
        return 'slug'; //When using route model binding, Laravel will now know to fetch the model that has the matching slug, as specified in the uri
    }

    //OTHER METHODS
    public function getImage()
    {
        if (!$this->image_filename){
            return config('codetube.buckets.images') . '/profile/default.png';
        }

        return config('codetube.buckets.images') . '/profile/' . $this->image_filename;
    }

    public function subscriptionsCount()
    {
        return $this->subscriptions->count();
    }

    public function totalVideoViews()
    {
        return $this->hasManyThrough(VideoView::class, Video::class)->count();
    }
}
