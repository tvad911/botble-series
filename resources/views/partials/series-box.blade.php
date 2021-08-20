<div class="series-box">
	<div class="center"><a href="{{ $series->url }}">{{ $series->name }}</a></div>
	@if(!empty($listPost))
		<ul class="series-list-ul">
			@foreach($listPost as $post)
				@if($post->id != $postId)
					<li class="series-list-li"><a href="{{ $post->url }}">{{ $post->name }}</a></li>
				@else
					<li class="series-list-li-current">{{ $post->name }}</li>
				@endif
			@endforeach
		</ul>
	@endif
</div>