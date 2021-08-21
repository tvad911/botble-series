<?php

namespace Botble\Series\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Exception;
use Botble\Series\Series;

class CreatedContentListener
{

    /**
     * Handle the event.
     *
     * @param CreatedContentEvent $event
     * @return void
     */
    public function handle(CreatedContentEvent $event)
    {
        try {
            if ($event->data && in_array(get_class($event->data),
                    config('plugins.series.general.supported', []))) {
                app(Series::class)->saveSeries($event->request, $event->data);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
