<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Channel;

class ChannelSubscriptionsController extends Controller
{
    public function show(Request $request, Channel $channel)
    {
    	//How many subscirptions
    	//Is the user subscribed
    	//Can the user subscribe

    	//Default response
    	$response = [
    		'count' => $channel->subscriptionsCount(),
    		'user_subscribed' => false,
    		'can_subscribe' => false,
    	];

    	//If a user is loged in, modify the response appropriatly
    	if($request->user()) {
    		$response = array_merge($response, [
    			'user_subscribed' => $request->user()->isSubscribedTo($channel),
    			'can_subscribe' => !$request->user()->ownsChannel($channel), //false if the user ownes the channel
    		]);
    	}

    	return response()->json([
    		'data' => $response
    	], 200);
    	
    }

    public function create(Request $request, Channel $channel)
    {
    	$this->authorize('subscribe', $channel);


    	$request->user()->subscriptions()->create([
    		'channel_id' => $channel->id
    	]);

    	return response()->json(null, 200);
    }

    public function delete(Request $request, Channel $channel)
    {
    	$this->authorize('unsubscribe', $channel);

    	$request->user()->subscriptions()->where('channel_id', $channel->id)->delete();

    	return response()->json(null, 200);

    }


}
