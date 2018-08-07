 <nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ config('app.url') . '/home' }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>


        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            
            <!-- SEARCH BAR -->

            <form action="config('app.url') . /search" mathod="get" class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" name="q" class="form-control" placeholder="Search" value="{{ Request::get('q') }}">
                </div>

                <button type="submit" class="btn btn-default">Search</button>
            </form>

            <ul class="nav navbar-nav">
                &nbsp;
            </ul>


            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ config('app.url') . '/login' }}">Login</a></li>
                    <li><a href="{{ config('app.url') . '/register' }}">Register</a></li>
                @else
                    <li><a href="{{ config('app.url') . '/subscriptions' }}">Subscriptions</a></li>
                    <li><a href="{{ config('app.url') . '/upload' }}">Upload</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>

                                <a href="{{ config('app.url') . '/videos/' }}">Your videos</a>
                                <a href="{{ config('app.url') . '/channel/' . $channel->slug }}">Your Channel</a>
                                <a href="{{ config('app.url') . '/channel/' . $channel->slug . '/edit' }}">Channel Settings</a>


                                <a href="{{ config('app.url') . '/logout' }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>



                                <form id="logout-form" action="{{ config('app.url') . '/logout' }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>


<!-- 

Since HTML does not support PUT, PATCH or DELETE:

<form action="/foo/bar" method="POST">
    <input type="hidden" name="_method" value="PUT">  OR   {{ method_field('PUT') }}
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

 -->