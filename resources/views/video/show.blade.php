@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                
                <!-- VIDEO IS PRIVATE NOTOFICATION -->
                @if ($video->isPrivate() && Auth::check() && $video->ownedByUser(Auth::user()))
                    <div class="alert alert-info">
                        Your video is currently private. Only you can see it.
                    </div>
                @endif

                <!-- THE VIDEO PLAYER -->
                @if ($video->isProcessed() && $video->canBeAccessed(Auth::user())) <!-- The signed in user ownes the video and the video is processed -->
                    <video-player video-uid="{{ $video->uid }}" video-url="{{ $video->getStreamUrl() }}" thumbnail-url="{{ $video->getListedVideoThumbnail() }}"></video-player>
                @endif


                <!-- VIDEO IS PROCESSING NOTIFICATION -->
                @if (!$video->isProcessed())
                    <div class="video-placeholder">
                        <div class="video-placeholder__header">
                            The video is processing. Come back a bit later.
                        </div>
                    </div>
                <!-- FOR GEUSTS, THE VIDEO IS PRIVATE NOTIFICATION -->
                @elseif(!$video->canBeAccessed(Auth::user()))
                    <div class="video-placeholder">
                        <div class="video-placeholder__header">
                            This video is private.
                        </div>
                    </div>
                @endif

                <!-- GENERAL VIDEO INFORMATION -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>{{ $video->title }}</h4>
                        
                        <!-- View count -->
                        <div class="pull-right">
                            <div class="video__views">
                                {{ $video->viewCount() }} {{ str_plural('view', $video->viewCount() ) }}
                            </div>

                            <!-- VOTING --> 
                            @if ($video->votesAllowed())
                                <video-voting video-uid="{{ $video->uid }}"></video-voting>
                            @endif

                        </div>

                        <!-- GENERAL CHANNEL INFORMATION -->
                        <div class="media">
                            <div class="media-left">
                                <a href="{{ config('app.url') . '/channel/' . $video->channel->slug }}">
                                    <img src="{{ $video->channel->getImage() }}" alt="{{ $video->channel->name }} image" style="width: 40px; height: 40px;">
                                </a>
                            </div>
                            <div class="media-body">
                                <a href="{{ config('app.url') . '/channel/' . $video->channel->slug }}" class="media-heading">{{ $video->channel->name }}</a>

                                <!-- SUBSCRIBE -->
                                <subscribe-button channel-slug="{{ $video->channel->slug }}"></subscribe-button>

                            </div>
                        </div>
                    </div>
                </div>
                
                
                <!-- VIDEO DESCRIPTION -->
                @if ($video->description) 
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {!! nl2br(e($video->description)) !!} <!-- escaping then new line to breaks (and then not escaping with !!)  ??? -->
                        </div>
                    </div>
                @endif
                <!-- VIDEO COMMENTS -->

                <div class="panel panel-default">
                    <div class="panel-body">
                        @if ($video->commentsAllowed())
                            <video-comments video-uid="{{ $video->uid }}"></video-comments> <!-- A vue custom component -->
                        @else
                            <p>Comments are disabled for this video.</p>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection