<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

//test routes
//Route::get('videos/{video}/jsonvotes', 'VideoVoteController@videoVotes');

Auth::routes();
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Route::get('/subscriptions', 'SubscriptionsController@index');

Route::post('/webhook/encoding', 'EncodingWebhookController@handle');

Route::get('videos/{video}', 'VideoController@show');

Route::post('videos/{video}/views', 'VideoViewController@create');

Route::get('/search', 'SearchController@index');

Route::get('/videos/{video}/votes', 'VideoVoteController@show');

Route::get('/videos/{video}/comments', 'VideoCommentController@index');

Route::get('/subscription/{channel}/', 'ChannelSubscriptionsController@show');

Route::get('/channel/{channel}', 'ChannelController@show');


//To assign middleware to all routes within a group, you may use the middleware key in the group attribute array. Middleware are executed in the order they are listed in the array:
Route::group(['middleware' => ['auth']], function() {
	//UPLOAD
	Route::get('/upload', 'VideoUploadController@index');
	Route::post('/upload', 'VideoUploadController@store');
	//VIDEOS
	Route::get('/videos', 'VideoController@index');
	Route::post('/videos', 'VideoController@store');
	Route::get('/videos/{video}/edit', 'VideoController@edit');
	Route::put('/videos/{video}', 'VideoController@update');
	Route::delete('/videos/{video}', 'VideoController@delete');
	//When injecting a model ID to a route or controller action, you will often query to retrieve the model that corresponds to that ID. Laravel route model binding provides a convenient way to automatically inject the model instances directly into your routes. For example, instead of injecting a user's ID, you can inject the entire User model instance that matches the given ID.

	//CHANNEL
	Route::get('/channel/{channel}/edit', 'ChannelSettingsController@edit'); //ROUTE MODEL BINDING
	Route::put('/channel/{channel}/edit', 'ChannelSettingsController@update'); //we use the same url, but different HTTP methods to call different methods on the controller

	//VOTES
	Route::post('/videos/{video}/votes', 'VideoVoteController@create');
	Route::delete('/videos/{video}/votes', 'VideoVoteController@remove');

	//COMMENTS
	Route::post('/videos/{video}/comments', 'VideoCommentController@create');
	Route::delete('/videos/{video}/comments/{comment}', 'VideoCommentController@delete');

	//SUBSCRIBE
	Route::post('/subscription/{channel}', 'ChannelSubscriptionsController@create');
	Route::delete('/subscription/{channel}', 'ChannelSubscriptionsController@delete');
});



/*
|--------------------------------------------------------------------------
| Examples
|--------------------------------------------------------------------------

//Route initiating a callback function:

Route::get('user/profile', function () {
    //
})->name('profile');

//Route initiating a controller method: (+ specifying a route name)

Route::get('user/profile', 'UserController@showProfile')->name('profile');

//Passing paramters and oprional parameters, remember to give optional values a default.

Route::get('posts/{post}/comments/{comment?}', function ($postId, $commentId = null) {
    //
});

//Once you have assigned a name to a given route, you may use the route's name when generating URLs or redirects via the global route function:

return redirect()->route('profile');

//When the route has parametes in it, you can pass them as the second parameter
$url = route('profile', ['id' => 1]);


/Assigning the same namespace to a group of controllers using the namespace parameter keyword (remember that the APP\Http\Controllers namespace has allready been included in your routes files by the RouteServiceProvider)

Route::group(['namespace' => 'Admin'], function() {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
});

//Route groups may also be used to handle sub-domain routing.

Route::group(['domain' => '{account}.myapp.com'], function () {
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});

//The prefix group attribute may be used to prefix each route in the group with a given URI

Route::group(['prefix' => 'admin'], function () {
    Route::get('users', function ()    {
        // Matches The "/admin/users" URL
    });
});

//To register an explicit binding, use the router's model method to specify the class for a given parameter. You should define your explicit model bindings in the boot method of the  RouteServiceProvider class:

	public function boot()
	{
	    parent::boot();

	    Route::model('user', App\User::class);
	}

	//Next, define a route that contains a {user} parameter:

	$router->get('profile/{user}', function(App\User $user) {
	    //
	});

//If you wish to use your own resolution logic, you may use the Route::bind method. The Closure you pass to the bind method will receive the value of the URI segment and should return the instance of the class that should be injected into the route: (an alternative to implicit and explicit model binding)

$router->bind('user', function ($value) {
    return App\User::where('name', $value)->first();
});


//You may use the current, currentRouteName, and currentRouteAction methods on the Route facade to access information about the route handling the incoming request:

$route = Route::current();

$name = Route::currentRouteName();

$action = Route::currentRouteAction();

*/