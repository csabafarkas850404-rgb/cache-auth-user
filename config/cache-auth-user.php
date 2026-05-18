<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    | Tegyünk egy egyedi prefixet a cache kulcs elé, amihez hozzáfűzzük az ID-t.
    |
    */
    'key_prefix' => 'auth_user_',

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (Time To Live)
    |--------------------------------------------------------------------------
    | Mennyi ideig tároljuk a felhasználót a cache-ben másodpercekben kifejezve.
    | Alapértelmezetten 1 nap (86400 másodperc).
    |
    */
    'ttl' => 86400,

    /*
    |--------------------------------------------------------------------------
    | Cache Store
    |--------------------------------------------------------------------------
    | Melyik cache driver-t használja a rendszer (null esetén a default-ot).
    |
    */
    'store' => null,
];