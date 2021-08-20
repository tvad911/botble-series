<?php

namespace Botble\Series\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Blog\Models\Post;

class Series extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'series';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_series');
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Series $series) {
            $series->posts()->detach();
        });
    }
}
