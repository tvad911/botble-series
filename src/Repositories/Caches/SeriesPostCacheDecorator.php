<?php

namespace Botble\Series\Repositories\Caches;

use Botble\Series\Repositories\Interfaces\SeriesPostInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class SeriesPostCacheDecorator extends CacheAbstractDecorator implements SeriesPostInterface
{
    /**
     * {@inheritDoc}
     */
    public function getPostBySeries($seriesId, $paginate = 12, $limit = 0, $sortBy = 'order', $orderBy = 'asc')
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
