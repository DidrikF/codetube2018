<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Jobs\UploadVideo;

class VideoUploadController extends Controller
{
    public function index()
    {
    	return view('video.upload');
    }


    public function store(Request $request)
    {
    	// grab user channel
    	$channel = $request->user()->channel()->first();

    	// lookup the video (which exists in the database, before the video file is actually uploaded)
    	$video = $channel->videos()->where('uid', $request->uid)->firstOrFail();

    	//move to temp location 

    	$request->file('video')->move(storage_path() . '/uploads', $video->video_filename);

    	//upload to s3

    	$this->dispatch(new UploadVideo(
            $video
            //$video->video_filename
        ));

    }




}
