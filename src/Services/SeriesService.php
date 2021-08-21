<?php

namespace Botble\Series\Services;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Series\Models\Series as SeriesModel;
use Botble\Series\Repositories\Interfaces\SeriesPostInterface;
use Botble\Series\Repositories\Interfaces\SeriesInterface;
use Botble\SeoHelper\SeoOpenGraph;
use Eloquent;
use Botble\Series\Series;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use RvMedia;
use SeoHelper;
use Theme;

class SeriesService
{
    /**
     * @param Eloquent $slug
     * @return array|Eloquent
     */
    public function handleFrontRoutes($slug)
    {
        if (!$slug instanceof Eloquent) {
            return $slug;
        }

        $condition = [
            'id'     => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check() && request()->input('preview')) {
            Arr::forget($condition, 'status');
        }

        if ($slug->reference_type !== SeriesModel::class) {
            return $slug;
        }

        $series = app(SeriesInterface::class)->getFirstBy($condition, ['*'], ['slugable']);

        if (!$series) {
            abort(404);
        }

        SeoHelper::setTitle($series->name)
            ->setDescription($series->description);

        $meta = new SeoOpenGraph;
        $meta->setDescription($series->description);
        $meta->setUrl($series->url);
        $meta->setTitle($series->name);
        $meta->setType('article');

        app(Series::class)->registerAssets();

        $posts = app(SeriesPostInterface::class)
                    ->getPostBySeries($series->id, theme_option('number_of_posts_in_a_category', 12));

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, SERIES_MODULE_SCREEN_NAME, $series);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Series'))
            ->add(SeoHelper::getTitle(), $series->url);

        return [
            'view'         => 'series',
            'default_view' => 'plugins/series::themes.series',
            'data'         => compact('series', 'posts'),
            'slug'         => $series->slug,
        ];
    }
}
