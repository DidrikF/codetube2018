<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

use App\Traits\Orderable;

use Storage;

//there are a more effective way to stream videos from a content delivery network, AWS has something called cloudfront which would be a more suitable place to load videos form
class Video extends Model
{
    use SoftDeletes, Searchable, Orderable; //A trait
    
    protected $fillable = [
        'title',
        'description',
        'uid',
        'video_filename',
        'video_id',
        'processed',
        'visibility',
        'allow_votes',
        'allow_comments',
        'processed_percentage',
    ];
    

    public function toSearchableArray() //adding a property to the angola version of the videos table
    {
        $properties = $this->toArray();

        $properties['visible'] = $this->isProcessed() && $this->isPublic(); //now public will ether be true or false, so that we can find the public vides with scout/angola
        //Angolas copy of the videos table will now say visibility = true for public videos!
        return $properties;
    }

    //RELATIONSHIPS
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function views()
    {
        return $this->hasMany(VideoView::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('reply_id');
    }

    public function getRouteKeyName()
    {
        return 'uid';
    }

    /* THIS IS NOW IN THE ORDERABLE TRAIT
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc'); //order by created as in descendig order (see it in action in the VideoController)
    }
    */
    public function isProcessed()
    {
        return $this->processed;
    }

    public function processedPercentage()
    {
        return $this->processed_percentage;
    }

    //when telestream works
    public function getThumbnail()
    {
        if (!$this->isProcessed())
        {
            //remember to make default thumbnail public in s3
            return config('codetube.buckets.videos') . '/default.png';
        }

        return config('codetube.buckets.videos') . '/' . $this->video_id . '_1.jpg';

    }

    public function votesAllowed()
    {
        return (bool) $this->allow_votes;
    }

    public function commentsAllowed()
    {
        return (bool) $this->allow_comments;
    }

    public function isPrivate()
    {
        return $this->visibility === 'private';
    }

    public function isPublic()
    {
        return $this->visibility === 'public';
    }

    public function ownedByUser(User $user)
    {
        return $this->channel->user->id === $user->id; //need to know about eloqunt
    }

    public function canBeAccessed($user = null)
    {
        if (!$user && $this->isPrivate()) {
            return false;
        }

        //if you are visiting a video page (you are not the owner) and the vidoe is private
        if($user && $this->isPrivate() && ($user->id !== $this->channel->user_id)) {
            return false;
        }

        return true; //now we know the user ownes the video and he is signed in (we are using the Auth facade to get the user model/object)
    }

    public function getStreamUrl()
    {
        return config('codetube.buckets.videos') . '/' . $this->video_id . '.mp4'; 
    }

    public function viewCount()
    {
        return $this->views()->count(); //is this working because of the relation ship defined in the views() method obove?
    }

    //if you need to count votes other places in your application, these functions should be extracted into a trait
    public function upVotes()
    {
        return $this->votes->where('type', 'up');
    }

    public function downVotes()
    {
        return $this->votes->where('type', 'down');
    }

    public function voteFromUser(User $user)
    {
        //->votes() are refering to the relationship (it's called a relationship method) between the Vote and Video model to query the database each time we call this method
        //We could instead take advantage of eager loading (->votes), where laravel will fetch all reults into a collection, which will be used to get data on sebsequent calls, instead of the database being queried all the time. Also the collection can be outputed as json, which I had problems doing the other way ??
        return $this->votes()->where('user_id', $user->id); //returnes all the users votes on the video (There should only be one)
    }

    //when telestream does not work
    public function getListedVideoThumbnail()
    {   
        if(Storage::disk('s3videos')->has($this->video_id . '_thumbnail.jpg')) {
            return config('codetube.buckets.videos') . '/' . $this->video_id . '_thumbnail.jpg';
        }
        return config('codetube.buckets.videos') . '/' . 'default_video_thumbnail' . '.png';
    }


    //public and processed video scopes (where clauses added to the query)
    public function scopeProcessed($query)
    {
        return $query->where('processed', true);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeVisible($query)
    {
        return $query->processed()->public();
    }
}