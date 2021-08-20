<?php

namespace Botble\Series\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface SeriesPostInterface extends RepositoryInterface
{
    /**
     * @return mixed
     */
    public function getPostBySeries($seriesId, $paginate = 12, $limit = 0, $sortBy = 'order', $orderBy = 'asc');
}
