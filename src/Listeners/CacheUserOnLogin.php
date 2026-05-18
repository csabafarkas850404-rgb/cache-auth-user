<?php

namespace CsaFa\CacheAuthUser\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cache;

class CacheUserOnLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if (!$event->user) {
            return;
        }

        $prefix = config('cache-auth-user.key_prefix', 'auth_user_');
        $ttl = config('cache-auth-user.ttl', 86400);
        $store = config('cache-auth-user.store');

        $cacheKey = $prefix . $event->user->getAuthIdentifier();

        Cache::store($store)->put($cacheKey, $event->user, $ttl);
    }
}