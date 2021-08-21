@if(!empty($series))
	<div class="series-meta">
		{{ __('plugins/series::series.meta_description', ['position' => $position, 'total' => $total]) }} <a href="{{ $series->url }}" class="series-{{ $series->id }}" title="{{ $series->name }}">{{ $series->name }}</a>
	</div>
@endif