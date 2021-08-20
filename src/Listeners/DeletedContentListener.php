<?php

namespace Botble\Series\Listeners;

use Botble\Base\Events\DeletedContentEvent;
use Exception;
use Botble\Series\Series;

class DeletedContentListener
{

    /**
     * Handle the event.
     *
     * @param DeletedContentEvent $event
     * @return void
     */
    public function handle(DeletedContentEvent $event)
    {
        try {
            if ($event->data && in_array(get_class($event->data),
                    config('plugins.series.general.supported', []))) {
                app(Series::class)->deleteSeries($event->request, $event->data);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
