<div class="series-list">
	<h4>{{ __('plugins/series::series.navigation') }}</h4>
	@if(!empty($prevPost))
		<span class="series-nav-left">{{ __('plugins/series::series.prev_post') }} <a href="{{ $prevPost->url }}">{{ $prevPost->name }}</a></span>
	@endif
	@if(!empty($nextPost))
		<span class="series-nav-right">{{ __('plugins/series::series.next_post') }} <a href="{{ $nextPost->url }}">{{ $nextPost->name }}</a></span>
	@endif
</div>