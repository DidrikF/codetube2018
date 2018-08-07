<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Channel;
use App\Models\Video;

class SearchController extends Controller
{
    public function index(Request $request)
    {
    	if(!$request->q) {
    		return back();
            //return redirect('/livedemo/codetube');
    	}

    	$channels = Channel::search($request->q)->take(3)->get(); //search is part of the Searchable trait that we imported on the channel model

    	$videos = Video::search($request->q)->where('visible', true)->get();

    	return view('search.index', [
    		'channels' => $channels,
    		'videos' => $videos,
    	]);
    }
}
