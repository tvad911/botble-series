<?php

namespace Botble\Series\Providers;

use Assets;
use Botble\Base\Models\BaseModel;
use Botble\Series\Services\SeriesService;
use Botble\Shortcode\Compilers\Shortcode;
use Eloquent;
use Illuminate\Contracts\Container\BindingResolutionException;
use Botble\Dashboard\Supports\DashboardWidgetInstance;
use Illuminate\Support\ServiceProvider;
use MetaBox;
use Illuminate\Support\Facades\Auth;
use Botble\Series\Series;
use Botble\Series\Models\Series as SeriesModel;
use Theme;
use Menu;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws \Throwable
     */
    public function boot()
    {
        if (defined('MENU_ACTION_SIDEBAR_OPTIONS')) {
            Menu::addMenuOptionModel(SeriesModel::class);
            add_action(MENU_ACTION_SIDEBAR_OPTIONS, [$this, 'registerMenuOptions'], 20);
        }
        add_action(BASE_ACTION_META_BOXES, [$this, 'addSeriesBox'], 20, 2);
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 22, 2);
        add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, [$this, 'handleSingleView'], 11);
        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, [$this, 'addSeriesContent'], 55, 2);

        if (function_exists('theme_option')) {
            add_action(RENDERING_THEME_OPTIONS_PAGE, [$this, 'addThemeOptions'], 36);
        }

        if (function_exists('shortcode')) {
            add_shortcode('series-meta', trans('plugins/series::series.series-meta-shortcode'),
                trans('plugins/series::series.add_series_meta_short_code'), [$this, 'render']);
            shortcode()->setAdminConfig('series-meta',
                view('plugins/series::partials.short-code-admin-config')->render());
        }
    }

    /**
     * @param string $context
     * @param BaseModel $object
     */
    public function addSeriesBox($context, $object)
    {
        if ($object && in_array(get_class($object),
                config('plugins.series.general.supported', [])) && $context == 'side') {

            MetaBox::addMetaBox(
                'series_wrap',
                trans('plugins/series::series.series_box'),
                [$this, 'seriesMetaField'],
                get_class($object),
                $context
            );
        }
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function seriesMetaField()
    {
        $listSeries = get_all_series();
        $series = null;
        $order = null;
        $seriesId = '';
        $args = func_get_args();

        if ($args[0] && $args[0]->id) {
            $series = series_meta_data($args[0]);
        }

        if (!empty($series)) {
            $seriesId = $series->series_id;
            $order = $series->order;
        }

        return view('plugins/series::series-box', compact('listSeries', 'seriesId', 'order'))->render();
    }

    /**
     * @param Eloquent $slug
     * @return array|Eloquent
     *
     * @throws BindingResolutionException
     */
    public function handleSingleView($slug)
    {
        return (new SeriesService)->handleFrontRoutes($slug);
    }

    /**
     * @param array $widgets
     * @param Collection $widgetSettings
     * @return array
     * @throws Throwable
     */
    public function registerDashboardWidgets($widgets, $widgetSettings)
    {
        if (!Auth::user()->hasPermission('series.index')) {
            return $widgets;
        }

        Assets::addScriptsDirectly(['/vendor/core/plugins/series/js/series.js']);

        return (new DashboardWidgetInstance)
            ->setPermission('series.index')
            ->setKey('widget_series')
            ->setTitle(trans('plugins/series::series.widget_series'))
            ->setIcon('fas fa-edit')
            ->setColor('#f3c200')
            ->setRoute(route('series.widget.list-series'))
            ->setBodyClass('scroll-table')
            ->setColumn('col-md-6 col-sm-6')
            ->init($widgets, $widgetSettings);
    }

    /**
     * @param string $screen
     * @param BaseModel $object
     */
    public function addSeriesContent($screen, $object)
    {
        Theme::asset()
            ->usePath(false)
            ->add('series', 'vendor/core/plugins/series/css/series.css');

        if ($screen == 'post' && $object) {
            $object->content = app(Series::class)->getContent($object);
        }
    }

    /**
     * @param Shortcode $shortcode
     * @return string
     */
    public function render($shortcode)
    {
        $postId = $shortcode->postid;
        if (!empty($postId)) {
            return app(Series::class)->getShortcode($postId);
        }

        return '';
    }

    /**
     * [addThemeOptions description]
     */
    public function addThemeOptions()
    {
        theme_option()
            ->setSection([
                'title'      => 'Series',
                'desc'       => 'Config for series',
                'id'         => 'opt-text-subsection-series',
                'subsection' => true,
                'icon'       => 'fa fa-edit',
                'fields'     => [
                    [
                        'id'         => 'series_sort_by',
                        'type'       => 'select',
                        'label'      => trans('plugins/series::series.sort_by'),
                        'attributes' => [
                            'name'    => 'series_sort_by',
                            'data'    => [
                                'order'    => 'Order',
                                'datetime' => 'Datetime'
                            ],
                            'value' => 'order',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id'         => 'series_order_by',
                        'type'       => 'select',
                        'label'      => trans('plugins/series::series.order_by'),
                        'attributes' => [
                            'name'    => 'series_order_by',
                            'data'    => [
                                'asc' => 'ASC',
                                'desc'  => 'DESC'
                            ],
                            'value'   => 'asc',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ]
                ]
            ]);
    }

    /**
     * Register sidebar options in menu
     * @throws Throwable
     */
    public function registerMenuOptions()
    {
        if (Auth::user()->hasPermission('series.index')) {
            Menu::registerMenuOptions(SeriesModel::class, trans('plugins/series::series.menu'));
        }
    }
}
