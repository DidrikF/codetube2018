<div class="row">

	<div class="col-sm-3">
		<a href="{{ config('app.url') . '/videos/' . $video->uid }}">
			<img src="{{ $video->getListedVideoThumbnail() }}" alt="{{ $video->title }} image" class="img-responsive">  
		</a>
	</div>
	<div class="col-sm-9">
		<a href="{{ config('app.url') . '/videos/' . $video->uid }}">{{ $video->title }}</a>

		@if ($video->description)
			<p>{{ $video->description }}</p>
		@else
			<p class="muted">No description</p>
		@endif

		<ul class="list-inline">
			<li><a href="{{ config('app.url') . '/channel/' . $video->channel->slug }}">{{ $video->channel->name }}</a></li>
			<li>{{ $video->created_at->diffForHumans() }}</li>
			<li>{{ $video->viewCount() }} {{ str_plural('view', $video->viewCount()) }}</li>
		</ul>

	</div>	

</div>