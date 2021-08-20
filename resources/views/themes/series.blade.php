<div>
    <h3>{{ $series->name }}</h3>
    {!! Theme::breadcrumb()->render() !!}
</div>
<div>
    @if ($posts->count() > 0)
        @foreach ($posts as $post)
            <article>
                <div>
                    <a href="{{ $post->url }}"><img src="{{ RvMedia::getImageUrl($post->image, 'small', false, RvMedia::getDefaultImage()) }}" alt="{{ $post->name }}"></a>
                </div>
                <div>
                    <header>
                        <h3><a href="{{ $post->url }}">{{ $post->name }}</a></h3>
                        <div><span>{{ $post->created_at->format('M d, Y') }}</span> <span>{{ $post->author->name }}</span> - <a href="{{ $post->categories->first()->url }}">{{ $post->categories->first()->name }}</a></div>
                    </header>
                    <div>
                        {!! do_shortcode(generate_shortcode('series-meta', ['postId' => $post->id])) !!}
                        <p>{{ $post->description }}</p>
                    </div>
                </div>
            </article>
        @endforeach
        <div>
            {!! $posts->links() !!}
        </div>
    @else
        <div>
            <p>{{ __('There is no data to display!') }}</p>
        </div>
    @endif
</div>
