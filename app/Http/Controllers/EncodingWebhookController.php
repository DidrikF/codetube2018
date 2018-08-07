<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video;


//since tele stream have no idea about our CSRF tokens, we exclude the notifications from being checked for a valid token (GO TO : Http/Middleware/VerifyCsrfToken.php)
class EncodingWebhookController extends Controller
{
    public function handle(Request $request)
    {
    	$event = camel_case($request->event);

    	if (method_exists($this, $event)) {
    		$this->{$event}($request); //calling a method based on what event tele stream have notified us about
    	}
    }

    protected function videoEncoded(Request $request)
    {
    	//Look up the video
    	$video = $this->getVideoByFilename($request->original_filename);
    	//update the processed column

    	$video->processed = true;
    	$video->video_id = $request->encoding_ids[0]; //id of the encoded video, as stored in s3
    	$video->save();
    }	

    protected function encodingProgress(Request $request)
    {
    	$video = $this->getVideoByFilename($request->original_filename);

    	$video->processed_percentage = $request->progress;
    	$video->save();
    }


    protected function getVideoByFilename($filename)
    {
    	return Video::where('video_filename', $filename)->firstOrFail(); //get the whole database entry of the video we just recieved a notificaiton about
    }


}
