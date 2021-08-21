@if ($series->count() > 0)
    <div class="scroller">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('core/base::tables.name') }}</th>
                <th>{{ trans('core/base::tables.created_at') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($series as $serie)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>@if ($serie->slug) <a href="{{ $serie->url }}" target="_blank">{{ Str::limit($serie->name, 80) }}</a> @else <strong>{{ Str::limit($serie->name, 80) }}</strong> @endif</td>
                    <td>{{ BaseHelper::formatDate($serie->created_at, 'd-m-Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if ($series instanceof Illuminate\Pagination\LengthAwarePaginator && $series->total() > $limit)
        <div class="widget_footer">
            @include('core/dashboard::partials.paginate', ['data' => $series, 'limit' => $limit])
        </div>
    @endif
@else
    @include('core/dashboard::partials.no-data', ['message' => trans('plugins/series::series.no_new_series_now')])
@endif