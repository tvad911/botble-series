<?php

namespace Botble\Series\Listeners;

use Botble\Base\Events\UpdatedContentEvent;
use Exception;
use Botble\Series\Series;

class UpdatedContentListener
{

    /**
     * Handle the event.
     *
     * @param UpdatedContentEvent $event
     * @return void
     */
    public function handle(UpdatedContentEvent $event)
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
