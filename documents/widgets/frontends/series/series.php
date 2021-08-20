<?php

use Botble\Widget\AbstractWidget;

class SeriesWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $widgetDirectory = 'series';

    /**
     * RecentPostsWidget constructor.
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        parent::__construct([
            'name'           => __('Series'),
            'description'    => __('Series widget.'),
            'number_display' => 5,
        ]);
    }
}
