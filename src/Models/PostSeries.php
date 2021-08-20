<?php

namespace Botble\Series\Models;

use Botble\Base\Models\BaseModel;

class PostSeries extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_series';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'post_id',
        'series_id',
        'order',
    ];
}
