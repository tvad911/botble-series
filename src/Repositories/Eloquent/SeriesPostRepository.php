<?php

namespace Botble\Series\Repositories\Eloquent;

use Botble\Series\Repositories\Interfaces\SeriesPostInterface;
use Botble\Blog\Repositories\Eloquent\PostRepository;
use Botble\Base\Enums\BaseStatusEnum;

class SeriesPostRepository extends PostRepository implements SeriesPostInterface
{
    /**
     * {@inheritDoc}
     */
    public function getPostBySeries($seriesId, $paginate = 12, $limit = 0, $sortBy = 'order', $orderBy = 'asc')
    {
        $data = $this->model
            ->where('posts.status', BaseStatusEnum::PUBLISHED)
            ->join('post_series', 'post_series.post_id', '=', 'posts.id')
            ->join('series', 'post_series.series_id', '=', 'series.id')
            ->where('post_series.series_id', $seriesId)
            ->select('posts.*')
            ->distinct()
            ->with('slugable');

        if($sortBy == 'order') {
            $data = $data->orderBy('post_series.order', $orderBy);
        }
        else{
            $data = $data->orderBy('posts.created_at', $orderBy);
        }

        if ($paginate != 0) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }

        if ($limit != 0) {
            return $this->applyBeforeExecuteQuery($data)->limit($limit)->get();
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
