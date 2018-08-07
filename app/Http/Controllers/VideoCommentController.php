<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\CreateVideoCommentRequest;
use App\Models\Video;
use App\Models\Comment;

use App\Transformers\CommentTransformer;

class VideoCommentController extends Controller
{
    public function index(Video $video)
    {
    	//Add policy to see if user is allowed to see comments if you want (in the case the video has comments turned of)

    	return response()->json(
    		fractal()->collection($video->comments()->latestFirst()->get())
    			->parseIncludes(['channel', 'replies', 'replies.channel']) //replies.channel addes the channel who posted the reply to the response
    			->transformWith(new CommentTransformer)
    			->toArray()
    	); //json end
    }

    public function create(CreateVideoCommentRequest $request, Video $video)
    {
        //authorize
        $this->authorize('comment', $video);

        $comment = $video->comments()->create([
            'body' => $request->body,
            'reply_id' => $request->get('reply_id', null),
            'user_id' => $request->user()->id,
        ]);

        return response()->json(
            fractal()->item($comment)
                ->parseIncludes(['channel', 'replies'])
                ->transformWith(new CommentTransformer)
                ->toArray()
        );

    }

    public function delete(Video $video, Comment $comment) 
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(null, 200);

    }

}
