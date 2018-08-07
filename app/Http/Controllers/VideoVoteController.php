<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CreateVoteRequest;
use App\Http\Requests;
use App\Models\Video;


class VideoVoteController extends Controller
{
    	
   public function create(CreateVoteRequest $request, Video $video)
    {
    	$this->authorize('vote', $video);

    	$video->voteFromUser($request->user())->delete(); //you cannot call delete on a collection

    	$video->votes()->create([
    		'type' => $request->type, //allready validated in the CreateVoteRequest
    		'user_id' => $request->user()->id,
    	]);

    	return response()->json(null, 200);
    }


    public function remove(Request $request, Video $video)
    {
    	$this->authorize('vote', $video);

    	$video->voteFromUser($request->user())->delete();
		
		return response()->json(null, 200);
    }


    public function show(Request $request, Video $video)
    {
    	    	//set default
    	$response = [
    		'up' => null,
    		'down' => null,
    		'can_vote' => $video->votesAllowed(),
    		'user_vote' => null,
    	];
    	//chack if votesa are allowed
    	if($video->votesAllowed())
    	{
    		$response['up'] = $video->upVotes()->count();
    		$response['down'] = $video->downVotes()->count();

    	}

    	//check user vote
    	if($request->user()) {
    		$voteFromUser = $video->voteFromUser($request->user())->first(); //returns a vote model
    		$response['user_vote'] = $voteFromUser ? $voteFromUser->type : null;
    	}

    	return response()->json([
    		'data' => $response
    	], 200);
    }
    

    //test method, to see what an eager loaded collection looks like
    public function videoVotes(Request $request, Video $video)
    {
        $viewCollection = $video->voteFromUser($request->user());

        return response()->json([
            'data' => $viewCollection
        ], 200);
    }
}
