<?php

namespace Jncinet\LaravelShare\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Database\Eloquent\Collection $sharers
 * @property \Illuminate\Database\Eloquent\Collection $shares
 */
trait Shareable
{
    /**
     * 内容是否被用户分享过
     *
     * @param Model $user
     * @return bool
     */
    public function isSharedBy(Model $user)
    {
        if (is_a($user, config('auth.providers.users.model'))) {
            if ($this->relationLoaded('sharers')) {
                return $this->sharers->contains($user);
            }

            return ($this->relationLoaded('shares') ? $this->shares : $this->shares())
                    ->where('user_id', $user->getKey())->count() > 0;
        }

        return false;
    }

    /**
     * 分享记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function shares()
    {
        return $this->morphMany(config('share.share_model'), 'shareable');
    }

    /**
     * 分享过内容的会员
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sharers()
    {
        return $this->belongsToMany(
            config('auth.providers.users.model'),
            config('share.share_table'),
            'shareable_id',
            'user_id'
        )
            ->where('shareable_type', $this->getMorphClass());
    }
}