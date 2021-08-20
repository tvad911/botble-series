<?php

namespace Botble\Series\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Series\Repositories\Interfaces\SeriesInterface;

class SeriesCacheDecorator extends CacheAbstractDecorator implements SeriesInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $selected = [], array $with = ['slugable'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgetSeries($limit = 5)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
