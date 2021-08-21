<?php

namespace Botble\Series\Providers;

use Botble\Series\Models\Series;
use Botble\Series\Models\PostSeries;
use Botble\Blog\Models\Post;
use Illuminate\Support\ServiceProvider;
use Botble\Series\Repositories\Caches\SeriesCacheDecorator;
use Botble\Series\Repositories\Eloquent\SeriesRepository;
use Botble\Series\Repositories\Interfaces\SeriesInterface;
use Botble\Series\Repositories\Caches\PostSeriesCacheDecorator;
use Botble\Series\Repositories\Eloquent\PostSeriesRepository;
use Botble\Series\Repositories\Interfaces\PostSeriesInterface;
use Botble\Series\Repositories\Caches\SeriesPostCacheDecorator;
use Botble\Series\Repositories\Eloquent\SeriesPostRepository;
use Botble\Series\Repositories\Interfaces\SeriesPostInterface;
use Botble\Base\Supports\Helper;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use SeoHelper;
use SlugHelper;

class SeriesServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(SeriesInterface::class, function () {
            return new SeriesCacheDecorator(new SeriesRepository(new Series));
        });

        $this->app->bind(PostSeriesInterface::class, function () {
            return new PostSeriesCacheDecorator(new PostSeriesRepository(new PostSeries));
        });

        $this->app->bind(SeriesPostInterface::class, function () {
            return new SeriesPostCacheDecorator(new SeriesPostRepository(new Post));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        SlugHelper::registerModule(Series::class, 'Series');
        SlugHelper::setPrefix(Series::class, 'series');

        $this->setNamespace('plugins/series')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web']);

        $this->app->register(EventServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                \Language::registerModule([Series::class]);
            }

            SeoHelper::registerModule([Series::class]);

            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-series',
                'priority' => 5,
                'parent_id' => 'cms-plugins-blog',
                'name' => 'plugins/series::series.name',
                // 'icon'        => 'fa fa-list',
                'icon' => null,
                'url' => route('series.index'),
                'permissions' => ['series.index'],
            ]);

            $this->app->register(HookServiceProvider::class);
        });
    }
}
