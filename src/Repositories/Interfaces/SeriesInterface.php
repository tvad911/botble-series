<?php

namespace Botble\Series\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface SeriesInterface extends RepositoryInterface
{
    /**
     * Get all galleries.
     *
     * @param array $with
     * @return mixed
     */
    public function getAll(array $selected = [], array $with = ['slugable']);

    /**
     * @return mixed
     */
    public function getDataSiteMap();

    /**
     * @return mixed
     */
    public function getWidgetSeries($limit = 5);
}
