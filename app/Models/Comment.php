<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Orderable;

//An eloquent model can be thought of as a powerfull query builder
class Comment extends Model
{	

	use SoftDeletes, Orderable;

	//define which model attributes you want to make mass assignable (white list)
    protected $fillable = [
    	'body',
    	'user_id',
    	'reply_id',
    ];



    //RELATIONSHIPS
    public function commentable()
    {
    	return $this->morphTo();
    }

    public function replies()
    {
    	//return $this->hasMany('App\Models\Comment', 'foreign_key', 'local_key');
    	return $this->hasMany(Comment::class, 'reply_id', 'id');
    }

    public function user()
    {
    	return $this->belongsTo(User::class); //on the user model: $this->hasMany(Comment::class); --> a one to many relationship!
    }





}
