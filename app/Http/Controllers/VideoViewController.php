<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video;

use Carbon\Carbon;

class VideoViewController extends Controller
{

	const BUFFER = 30;

    public function create(Request $request, Video $video)
    {
    	if(!$video->canBeAccessed($request->user())) {
    		return;
    	}

    	//grab last view
    	//check if in buffer
    	//return if too soone
    	if($request->user()) {
    		$lastUserView = $video->views()->latestByUser($request->user())->first();

    		if($this->withinBuffer($lastUserView)) { //if within the buffer, do not log a view
    			return; //this will be a 200 response, but with NO responce body
    		}
    	}

    	//get last view for current ip
    	//check if in buffer 
    	//return if too soone
    	$lastIpView = $video->views()->latestByIp($request->ip())->first();
		if($this->withinBuffer($lastIpView)) { //if within the buffer, do not log a view
    		return; //this will be a 200 response, but with NO responce body
    	}


    	$video->views()->create([
    		'user_id' => $request->user() ? $request->user()->id : null,
    		'ip' => $request->ip(),
    	]);

    	return response()->json(null, 200); //the response body is an emtpy json object = {}
    }

    protected function withinBuffer($view)
    {
    	return $view && $view->created_at->diffInSeconds(Carbon::now()) < self::BUFFER;
    }

    
}
