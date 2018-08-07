<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth; //this is an alias, look in config/app.php


//View composers are callbacks or class methods that are called when a view is rendered. If you have data that you want to be bound to a view each time that view is rendered, a view composer can help you organize that logic into a single location.


class NavigationComposer
{
	public function compose(View $view)
	{
		if(!Auth::check())
		{
			return;
		}

		//Sharing data with the navigation view allways (if user is logged in)
		$view->with('channel', Auth::user()->channel()->first());
	}
}