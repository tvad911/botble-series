<?php

namespace Botble\Series\Listeners;

use SiteMapManager;
use Botble\Series\Repositories\Interfaces\SeriesInterface;

class RenderingSiteMapListener
{
    /**
     * @var SeriesInterface
     */
    protected $seriesRepository;

    /**
     * RenderingSiteMapListener constructor.
     * @param SeriesInterface $seriesRepository
     */
    public function __construct(SeriesInterface $seriesRepository)
    {
        $this->seriesRepository = $seriesRepository;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        SiteMapManager::add(route('public.series'), '2020-11-15 00:00', '0.8', 'weekly');

        $series = $this->seriesRepository->getDataSiteMap();

        foreach ($series as $serie) {
            SiteMapManager::add($serie->url, $serie->updated_at, '0.8', 'daily');
        }
    }
}
