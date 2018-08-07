@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Search for "{{ Request::get('q') }}"</div>

                <div class="panel-body">
                    <h4>Channels</h4>
                    @if($channels->count())                       
                        @foreach($channels as $channel)
                            <div class="well">
                                <div class="media">
                                    <div class="media-left">
                                        <a href="{{ config('app.url') . '/channel/' . $channel->slug }}">
                                            <img src="{{ config('app.url') . $channel->getImage() }}" alt="{{ $channel->name }} image" class="media-object"> 
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <a href="{{ config('app.url') . '/channel/' . $channel->slug }}" class="media-heading">{{ $channel->name }}</a>
                                        <ul class="list-inline">
                                            <li>
                                                <subscribe-button channel-slug="{{ $channel->slug }}"></subscribe-button>
                                            </li>
                                            <li class="pull-right">
                                                {{ $channel->totalVideoViews() }} video {{ str_plural('view', $channel->totalVideoViews()) }}
                                            </li>
                                        </ul>
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No channels found.</p>
                    @endif

                    <h4>Videos</h4>
                    @if ($videos->count())    
                        @foreach ($videos as $video)
                            <div class="well">
                                @include ('video.partials._video_result', [
                                    'video' => $video
                                ])

                            </div>
                        @endforeach
                    @else
                        <p>No videos found.</p>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
