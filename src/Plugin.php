<?php

namespace Botble\Series;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('post_series');
        Schema::dropIfExists('series');

        app(DashboardWidgetInterface::class)->deleteBy(['name' => 'widget_series']);
    }
}
