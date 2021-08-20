<?php

return [
    [
        'name' => 'Series',
        'flag' => 'series.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'series.create',
        'parent_flag' => 'series.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'series.edit',
        'parent_flag' => 'series.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'series.destroy',
        'parent_flag' => 'series.index',
    ],
];
