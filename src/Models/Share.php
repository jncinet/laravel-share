<?php

namespace Jncinet\LaravelShare\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jncinet\LaravelShare\Events\Shared;
use Jncinet\LaravelShare\Events\Unshared;

class Share extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => Shared::class,
        'deleted' => Unshared::class,
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('share.share_table');

        parent::__construct($attributes);
    }

    /**
     * 关联内容
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function shareable()
    {
        return $this->morphTo();
    }

    /**
     * 发布评论的会员
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(
            config('share.user_information_model'),
            'user_id'
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithType(Builder $query, $type)
    {
        return $query->where('shareable_type', app($type)->getMorphClass());
    }
}