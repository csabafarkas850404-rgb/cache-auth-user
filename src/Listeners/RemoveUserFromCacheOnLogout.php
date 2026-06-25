<?php

namespace CsaFa\CacheAuthUser\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cache;

class RemoveUserFromCacheOnLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        if (!$event->user) {
            return;
        }

        $prefix = config('cache-auth-user.key_prefix', 'auth_user_');
        $store = config('cache-auth-user.store');

        $cacheKey = $prefix . $event->user->getAuthIdentifier();

        Cache::store($store)->forget($cacheKey);
    }
}
