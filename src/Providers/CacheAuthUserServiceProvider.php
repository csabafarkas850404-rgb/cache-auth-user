<?php

namespace CsaFa\CacheAuthUser\Providers;

use CsaFa\CacheAuthUser\Extensions\CacheableEloquentUserProvider;
use CsaFa\CacheAuthUser\Listeners\CacheUserOnLogin;
use CsaFa\CacheAuthUser\Listeners\RemoveUserFromCacheOnLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class CacheAuthUserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/cache-auth-user.php', 'cache-auth-user'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/cache-auth-user.php' => config_path('cache-auth-user.php'),
            ], 'cache-auth-user-config');
        }

        Event::listen(
            Login::class,
            CacheUserOnLogin::class
        );

        Event::listen(
            Logout::class,
            RemoveUserFromCacheOnLogout::class
        );

        Auth::provider('cacheable-eloquent', function ($app, array $config) {
            return new CacheableEloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
