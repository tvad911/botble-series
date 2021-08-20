<?php

use Botble\Series\Repositories\Interfaces\SeriesInterface;
use Botble\Series\Repositories\Interfaces\PostSeriesInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

if (!function_exists('series_meta_data')) {
    /**
     * @param Model $object
     * @param array $select
     * @return array
     */
    function series_meta_data($object, array $select = ['series_id', 'order'])
    {
        $meta = app(PostSeriesInterface::class)->getFirstBy([
            'post_id' => $object->id,
        ], $select);

        return $meta;
    }
}

if (!function_exists('get_all_series')) {
    /**
     * @param Model $object
     * @param array $select
     * @return array
     */
    function get_all_series()
    {
        $series = app(SeriesInterface::class)->getAll(['id', 'name']);

        $listSeries = [
            '' => __('None'),
        ];

        foreach ($series as $serie) {
            $listSeries[$serie->id] = $serie->name;
        }

        return $listSeries;
    }
}

if (!function_exists('get_widget_series')) {
    /**
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    function get_widget_series($limit)
    {
        return app(SeriesInterface::class)->getWidgetSeries($limit);
    }
}
