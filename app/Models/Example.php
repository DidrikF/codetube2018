<?php

namespace App\Models;


//use App\Scopes\AgeScope;

use Illuminate\Database\Eloquent\Model;

//An eloquent model can be thought of as a powerfull query builder
class Example extends Model
{	//define which model attributes you want to make mass assignable (white list)
    protected $fillable = [
    	'body',
    	'user_id',
    	'reply_id',
    ];

    //protected $table = 'my_comment'; //this will override the default database table name, that is assumed by eloquent (comments in this instance)
    //public $timestamps = false; //I do not want eloquent to manage created_at and updated_at columns in my database table
    //protected/public $primaryKey = name; //override the default asumption that 'id' is the primary key of the table
    //public $incrementing = flase; // If you wish to use a non-incrementing or a non-numeric primary key
    //protected $dateFormat = 'U'; //specify dateformat on timestamps

    //const CREATED_AT = 'creation_date'; //customize the names of the columns used to store the timestamps
    //const UPDATED_AT = 'last_update';

    //protected $connection = 'connection-name'; //override default database connection

    //protected $guarded = ['price']; // contain an array of attributes that you do not want to be mass assignable (black list)


    //RELATIONSHIPS
    public function commentable()
    {
    	return $this->morphTo();
    }

    public function replies()
    {
    	return $this->hasMany(Comment::class, 'reply_id', 'id')
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }



    //For Eloquent methods like all and get which retrieve multiple results, an instance of  Illuminate\Database\Eloquent\Collection will be returned. 
    //The Collection class provides a variety of helpful methods for working with your Eloquent results:

    //$comment->forceDelete(); //to delete a softDelete model from the database


    //Query Scopes : constraits to the querys for a model
    //Global scopes allow you to add constraints to all queries for a given model.
    // - Global scopes allow you to add constraints to all queries for a given model

    /* ASSIGNING A GLOBAL SCOPE TO A MODEL
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AgeScope); 
    }
    /*

    /* LOCAL SCOPES (just methods addning a where() clause ot the query you are bulding)
    To define a scope, simply prefix an Eloquent model method with scope. Scopes should always return a query builder instance:
    public function scopePopular($query)
    {
        return $query->where('votes', '>', 100);
    }
    */


    /*
    Eloquent models fire several events, allowing you to hook into various points in the model's 
    lifecycle using the following methods: creating, created, updating, updated, saving, saved, deleting,  
    deleted, restoring, restored. Events allow you to easily execute code each time a specific model 
    class is saved or updated in the database.



    */



}
