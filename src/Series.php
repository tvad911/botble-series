<?php

namespace Botble\Series;

use Botble\Series\Repositories\Interfaces\PostSeriesInterface;
use Botble\Series\Repositories\Interfaces\SeriesInterface;
use Botble\Series\Repositories\Interfaces\SeriesPostInterface;
use Illuminate\Support\Arr;
use Theme;

class Series
{
    /**
     * @var PostSeriesInterface
     */
    protected $postSeriesRepository;

    /**
     * Series constructor.
     * @param PostSeriesInterface $postSeriesRepository
     */
    public function __construct(PostSeriesInterface $postSeriesRepository)
    {
        $this->postSeriesRepository = $postSeriesRepository;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Eloquent|false $data
     * @throws \Exception
     */
    public function saveSeries($request, $data)
    {
        if ($data != false && in_array(get_class($data), config('plugins.series.general.supported', []))) {
            if (empty($request->input('series'))) {
                $this->postSeriesRepository->deleteBy([
                    'post_id' => $data->id
                ]);
            }

            $postSeries = $this->postSeriesRepository->getFirstBy([
                'post_id' => $data->id
            ]);

            if (!$postSeries) {
                $postSeries = $this->postSeriesRepository->getModel();
                $postSeries->post_id = $data->id;
            }

            $postSeries->series_id = $request->input('series');
            $postSeries->order = $request->input('order');

            $this->postSeriesRepository->createOrUpdate($postSeries);
        }
    }

    /**
     * @param \Eloquent|false $data
     * @return bool
     * @throws \Exception
     */
    public function deleteSeries($data)
    {
        if (in_array(get_class($data), config('plugins.series.general.supported', []))) {
            $this->postSeriesRepository->deleteBy([
                'post_id' => $data->id
            ]);
        }

        return true;
    }

    /**
     * @return $this
     */
    public function registerAssets()
    {
        Theme::asset()
            ->usePath(false)
            ->add('series-css', asset('vendor/core/plugins/series/css/series.css'), [], [], '1.0.0');

        return $this;
    }

    public function getContent($object)
    {
        /**
         * 1. Get Series info
         * Truy???n post_id l???y ra series info
         * 2. T??? series l???y ra danh s??ch post
         * Order theo: order && post created_at
         * 3. ???????c 1 m???ng danh s??ch post
         * S???p x???p order
         * 4. Ki???m tra
         * Count s??? post trong series --> L???y v??? tr?? c???a post trong m???ng --> Xem v??? tr?? + show ra th??ng tin m???ng b??i vi???t trong series
         * T??? m???ng ??ang c?? ????? show ra th??ng tin m???ng, b??i vi???t hi???n t???i v?? k??? ti???p
         */
        $series = null;
        $postId = $object->id ?? 0;
        $listPost = null;
        $html = '';
        $position = 0;
        $total = 0;
        $arrayPost = [];
        $nextPost = null;
        $prevPost = null;
        $sortBy = theme_option('series_sort_by', 'order');
        $orderBy = theme_option('series_order_by', 'asc');

        if (!empty($postId)) {
            $postSeries = $this->postSeriesRepository->getFirstBy([
                'post_id' => $postId
            ]);

            if (!empty($postSeries)) {
                $series = app(SeriesInterface::class)->getFirstBy([
                    'id' => $postSeries->series_id
                ]);
            }

            if (!empty($series)) {
                $listPost = app(SeriesPostInterface::class)
                    ->getPostBySeries($series->id, 0, 0, $sortBy, $orderBy);
            }

            if (!empty($listPost)) {
                $total = $listPost->count();

                $arrayPost = $listPost->toArray();
                foreach ($arrayPost as $key => $value) {
                    if ($value['id'] == $postId) {
                        $position = $key + 1;

                        break;
                    } else {
                        $position = 0;
                    }
                }


                $html = view('plugins/series::partials.series-meta', compact('position', 'total', 'series'));
                $html .= view('plugins/series::partials.series-box', compact('postId', 'series', 'listPost'));
                $html .= $object->content;

                if ($total > 1 && $position >= 1) {
                    if ($position - 1 >= 1) {
                        $prevPost = $listPost->firstWhere('id', '=', $arrayPost[$position - 2]['id']);
                    }

                    if ($position + 1 <= $total) {
                        $nextPost = $listPost->firstWhere('id', '=', $arrayPost[$position]['id']);
                    }

                    $html .= view('plugins/series::partials.series-list', compact('nextPost', 'prevPost'));
                }
            }
        }

        return $html;
    }

    /**
     * [description description]
     * @param  [type] $object [description]
     * @return [type]         [description]
     */
    public function getShortcode($postId)
    {
        $series = null;
        $listPost = null;
        $html = '';
        $position = 0;
        $total = 0;
        $sortBy = theme_option('series_sort_by', 'order');
        $orderBy = theme_option('series_order_by', 'asc');

        if (!empty($postId)) {
            $postSeries = $this->postSeriesRepository->getFirstBy([
                'post_id' => $postId
            ]);

            if (!empty($postSeries)) {
                $series = app(SeriesInterface::class)->getFirstBy([
                    'id' => $postSeries->series_id
                ]);
            }

            if (!empty($series)) {
                $listPost = app(SeriesPostInterface::class)
                    ->getPostBySeries($series->id, 0, 0, $sortBy, $orderBy);
            }

            if (!empty($listPost)) {
                $total = $listPost->count();

                $arrayPost = $listPost->toArray();
                foreach ($arrayPost as $key => $value) {
                    if ($value['id'] == $postId) {
                        $position = $key + 1;

                        break;
                    } else {
                        $position = 0;
                    }
                }

                $html = view('plugins/series::partials.series-meta', compact('position', 'total', 'series'));
            }
        }

        return $html;
    }
}
