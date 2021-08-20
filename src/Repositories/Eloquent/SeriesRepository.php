<?php

namespace Botble\Series\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Series\Repositories\Interfaces\SeriesInterface;
use Botble\Base\Enums\BaseStatusEnum;

class SeriesRepository extends RepositoriesAbstract implements SeriesInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll(array $selected = [], array $with = [])
    {
        $data = $this->model
            ->with($with)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->select($selected)
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        $data = $this->model
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgetSeries($limit = 5)
    {
        $data = $this->model->where(['status' => BaseStatusEnum::PUBLISHED]);

        $data = $data->limit($limit)
            ->with('slugable')
            ->select('series.*')
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
