<?php

namespace CsaFa\CacheAuthUser\Extensions;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;

class CacheableEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        $prefix = config('cache-auth-user.key_prefix', 'auth_user_');
        $ttl = config('cache-auth-user.ttl', 86400);
        $store = config('cache-auth-user.store');

        $cacheKey = $prefix . $identifier;

        $cachedData = Cache::store($store)->get($cacheKey);

        if ($cachedData && is_array($cachedData)) {
            $model = $this->createModel();

            return $model->newInstance($cachedData, true);
        }

        $user = parent::retrieveById($identifier);

        if ($user) {
            Cache::store($store)->put($cacheKey, $user->getAttributes(), $ttl);
        }

        return $user;
    }
}