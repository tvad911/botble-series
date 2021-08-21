<?php

namespace Botble\Series\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Series\Http\Requests\SeriesRequest;
use Botble\Series\Repositories\Interfaces\SeriesInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Series\Tables\SeriesTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Series\Forms\SeriesForm;
use Botble\Base\Forms\FormBuilder;

class SeriesController extends BaseController
{
    /**
     * @var SeriesInterface
     */
    protected $seriesRepository;

    /**
     * @param SeriesInterface $seriesRepository
     */
    public function __construct(SeriesInterface $seriesRepository)
    {
        $this->seriesRepository = $seriesRepository;
    }

    /**
     * @param SeriesTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(SeriesTable $table)
    {
        page_title()->setTitle(trans('plugins/series::series.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/series::series.create'));

        return $formBuilder->create(SeriesForm::class)->renderForm();
    }

    /**
     * @param SeriesRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(SeriesRequest $request, BaseHttpResponse $response)
    {
        $series = $this->seriesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SERIES_MODULE_SCREEN_NAME, $request, $series));

        return $response
            ->setPreviousUrl(route('series.index'))
            ->setNextUrl(route('series.edit', $series->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $series = $this->seriesRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $series));

        page_title()->setTitle(trans('plugins/series::series.edit') . ' "' . $series->name . '"');

        return $formBuilder->create(SeriesForm::class, ['model' => $series])->renderForm();
    }

    /**
     * @param int $id
     * @param SeriesRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, SeriesRequest $request, BaseHttpResponse $response)
    {
        $series = $this->seriesRepository->findOrFail($id);

        $series->fill($request->input());

        $series = $this->seriesRepository->createOrUpdate($series);

        event(new UpdatedContentEvent(SERIES_MODULE_SCREEN_NAME, $request, $series));

        return $response
            ->setPreviousUrl(route('series.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $series = $this->seriesRepository->findOrFail($id);

            $this->seriesRepository->delete($series);

            event(new DeletedContentEvent(SERIES_MODULE_SCREEN_NAME, $request, $series));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $series = $this->seriesRepository->findOrFail($id);
            $this->seriesRepository->delete($series);
            event(new DeletedContentEvent(SERIES_MODULE_SCREEN_NAME, $request, $series));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getWidgetSeries(Request $request, BaseHttpResponse $response)
    {
        $limit = (int)$request->input('paginate', 10);

        $series = $this->seriesRepository->advancedGet([
            // 'condition' => ['status' => BaseStatusEnum::PUBLISHED],
            'with' => ['slugable'],
            'order_by' => ['created_at' => 'desc'],
            'paginate' => [
                'per_page' => $limit,
                'current_paged' => (int)$request->input('page'),
            ],
        ]);

        return $response
            ->setData(view('plugins/series::widgets.series', compact('series', 'limit'))->render());
    }
}
