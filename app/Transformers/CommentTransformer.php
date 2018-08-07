<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Models\Comment;


//A fairly complex endpoint
class CommentTransformer extends TransformerAbstract
{
	
	protected $availableIncludes = [
		'channel', 'replies'
	];



	public function transform(Comment $comment)
	{

		

		return [
			'id' => $comment->id,
			'user_id' => $comment->user_id,
			'body' => $comment->body,
			'created_at' => $comment->created_at->toDateTimeString(),
			'created_at_human' => $comment->created_at->diffForHumans(),
		];
	}

	//the VideoCommentController will add this to the response using the ->parseIncludes() function
	public function includeChannel(Comment $comment)
	{
		return $this->item($comment->user->channel->first(), new ChannelTransformer); //the channel data that we return is decided by the ChannelTransformer
	}

	public function includeReplies(Comment $comment)
	{
		return $this->collection($comment->replies, new CommentTransformer);
	}


}