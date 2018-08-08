<?php

namespace App\Http\Controllers;

use App\Models\Video;

use App\Http\Requests\VideoUpdateRequest;
use App\Http\Requests\VideoCreateRequest;

use Illuminate\Http\Request;

use App\Http\Requests;

class VideoController extends Controller
{

	public function show(Video $video)
	{
		return view('video.show', [
			'video' => $video,
		]);
	}

	public function index(Request $request)
	{
		//this is now possible, even it there is no direct relationship between a user and a video, by defingin a hasManyThrough relationship on the user model
		$videos = $request->user()->videos()->latestFirst()->paginate(10); //gets the videos associated with the user through his/her channels
		//dd($videos);
		return view('video.index', [
			'videos' => $videos,
		]);
	}


	public function edit(Video $video)
	{
		//authorize
		$this->authorize('edit', $video);
		//
		return view('video.edit', [
			'video' => $video,
		]);
	}

	public function update(VideoUpdateRequest $request, Video $video)
	{
		$this->authorize('update', $video);

		$video->update([
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'allow_votes' => $request->has('allow_votes'),
            'allow_comments' => $request->has('allow_comments'),
        ]);

        if($request->ajax()) { //If it is an ajax request
        	return response()->json(null, 200);
        }
				//return response()->json(null, 200);
        return redirect()->back(); //if not an ajax request

	}
    	
    public function store(VideoCreateRequest $request)
    {
    	//abort(500); //To test the client side, when uploading "failes"
    	//generete uid
    	$uid = uniqid(true);
    	//grap user channel
    	$channel = $request->user()->channel()->first();

    	$video = $channel->videos()->create([ //Create new entry in the videos table with the channel_id equal to the channel object from above.
    			'uid' => $uid,
    			'title' => $request->title,
    			'description' => $request->description,
    			'visibility' => $request->visibility,
    			'video_filename' => "{$uid}.{$request->extension}",
                
                //Added here because telestream trail over
                'video_id' => "{$uid}",
                'processed_percentage' => 100,
                //'processed' => true, //set when the video has been uploaded to s3 in the UploadVideo job
    		]);
    	
        //resopond with the UID (because now the video exists in the database, but the video file is not yet uploaded)
    	return response()->json([
    			'data' => [
    				'uid' => $uid,
    			]
    		]);

    }

    public function delete(Video $video)
    {
    	//authorize
    	$this->authorize('delete', $video);

    	$video->delete(); //sets the deleted_at field with a time, and somehow eloqent or the query builder knows to exclude them...

    	return redirect()->back();
    }

}
