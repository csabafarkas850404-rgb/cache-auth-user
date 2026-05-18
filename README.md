# Cache Auth User for Laravel 13

A lightweight, non-intrusive Laravel 13 package that caches the authenticated user instance. Instead of querying the database on every `auth()->user()` call, it retrieves the user model directly from the cache using Laravel's native cache store.

It automatically primes the cache upon successful login using events, without messing with Laravel's core authentication flow.

## Features

- **Performance Boost:** Eliminates repetitive database queries for the authenticated user.
- **Seamless Integration:** Uses Laravel's native event listeners (`Login`) and a custom User Provider.
- **Configurable:** Easily customize cache keys, TTL (Time To Live), and specific cache stores.

## Installation

You can install the package via composer (ensure your local repository or VCS path is configured if installing from a local directory):

```bash
composer require csafa/cache-auth-user
```
The package will automatically register its service provider.


## Configuration

Publish the configuration file using the Artisan CLI:
```php
php artisan vendor:publish --provider="CsaFa\CacheAuthUser\Providers\CacheAuthUserServiceProvider" --tag="cache-auth-user-config"
```

This will create a config/cache-auth-user.php file:
```php
return [
    // Prefix added to the user ID in the cache
    'key_prefix' => 'auth_user_',

    // Cache expiration time in seconds (default: 1 day)
    'ttl' => 86400,

    // Specific cache store to use (null defaults to your main cache driver)
    'store' => null,
];
```

## Activation

To activate the cached user provider, open your application's central authentication configuration file (config/auth.php) and change the driver of your user provider to cacheable-eloquent:
```php
// config/auth.php

'providers' => [
    'users' => [
        'driver' => 'cacheable-eloquent', // Changed from 'eloquent'
        'model' => App\Models\User::class,
    ],
],
```

## How It Works
- On Login: When a user logs in, the CacheUserOnLogin listener intercepts the Illuminate\Auth\Events\Login event and stores the user model into the cache.
- On Request: When auth()->user() or Auth::user() is called, the CacheableEloquentUserProvider uses Cache::remember to serve the user directly from the cache. If the cache expires or is cleared, it falls back to a database query and re-caches the result.

## Recommendation (Cache Busting)

To avoid serving stale data when a user updates their profile, it is highly recommended to clear the cache using Eloquent Model Observers or Model Events in your main application:
```php
// app/Models/User.php

protected static function booted()
{
    static::saved(function ($user) {
        \Illuminate\Support\Facades\Cache::forget(config('cache-auth-user.key_prefix') . $user->id);
    });

    static::deleted(function ($user) {
        \Illuminate\Support\Facades\Cache::forget(config('cache-auth-user.key_prefix') . $user->id);
    });
}
```


```bash

```