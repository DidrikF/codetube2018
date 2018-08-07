<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $videos = Video::where('visibility', 'public')->where('processed', true)->get();
        return view('home', [
            'videos' => $videos,
        ]);
    }
}
