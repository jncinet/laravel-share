<?php

namespace Jncinet\LaravelShare\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Database\Eloquent\Collection $shares
 * @property \Illuminate\Database\Eloquent\Collection $unShares
 */
trait Sharer
{
    /**
     * 创建分享记录
     *
     * @param Model $object
     */
    public function share(Model $object)
    {
        /* @var \Jncinet\LaravelShare\Traits\Shareable $object */
        if (!$this->hasShared($object)) {
            $share = app(config('share.share_model'));
            $share->user_id = $this->getKey();

            $object->shares()->save($share);
        }
    }

    /**
     * 删除分享记录
     *
     * @param Model $object
     * @throws \Exception
     */
    public function unShare(Model $object)
    {
        /* @var \Jncinet\LaravelShare\Traits\Shareable $object */
        $relation = $object->shares()
            ->where('shareable_id', $object->getKey())
            ->where('shareable_type', $object->getMorphClass())
            ->where('user_id', $this->getKey())
            ->first();

        if ($relation) {
            $relation->delete();
        }
    }

    /**
     * 用户是否分享了某条内容
     *
     * @param Model $object
     * @return bool
     */
    public function hasShared(Model $object)
    {
        return ($this->relationLoaded('shares') ? $this->shares : $this->shares())
                ->where('shareable_id', $object->getKey())
                ->where('shareable_type', $object->getMorphClass())
                ->count() > 0;
    }

    /**
     * 用户所有分享内容
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shares()
    {
        return $this->hasMany(config('share.share_model'), 'user_id', $this->getKeyName());
    }

    /**
     * 用户分享指定类型内容列表
     *
     * @param string $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getShareItems($model)
    {
        return app($model)->whereHas(
            'sharers',
            function ($query) {
                return $query->where('user_id', $this->getKey());
            }
        );
    }
}