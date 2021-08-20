@if (is_plugin_active('series'))
    @php
        $series = get_widget_series($config['number_display']);
    @endphp
    @if ($series->count())
        @if ($sidebar == 'footer_sidebar')
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="widget widget--transparent widget__footer">
                    @else
                        <div class="widget widget__recent-post">
                            @endif
                            <div class="widget__header">
                                <h3 class="widget__title">{{ $config['name'] }}</h3>
                            </div>
                            <div class="widget__content">
                                <ul @if ($sidebar == 'footer_sidebar') class="list list--light list--fadeIn" @endif>
                                    @foreach ($series as $serie)
                                        <li>
                                            @if ($sidebar == 'footer_sidebar')
                                                <a href="{{ $serie->url }}" data-number-line="2">{{ $serie->name }}</a>
                                            @else
                                                <article class="post post__widget clearfix">
                                                    <header class="post__header">
                                                        <h5 class="post__title"><a href="{{ $serie->url }}" data-number-line="2">{{ $serie->name }}</a></h5>
                                                        <div class="post__meta"><span class="post__created-at">{{ $serie->created_at->translatedFormat('M d, Y') }}</span></div>
                                                    </header>
                                                </article>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @if ($sidebar == 'footer_sidebar')
                </div>
        @endif
    @endif
@endif
