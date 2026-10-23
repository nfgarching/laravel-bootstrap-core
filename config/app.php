<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Startpage
    |--------------------------------------------------------------------------
    |
    |
    */

    'start_page' => env('APP_START_PAGE', 'page::dashboard'),

    /*
    |--------------------------------------------------------------------------
    | Application Versione
    |--------------------------------------------------------------------------
    |
    |
    */

    'version' => env('APP_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Rollen für Package-Routen
    |--------------------------------------------------------------------------
    |
    | Nur wirksam, wenn spatie/laravel-permission installiert ist (siehe
    | Nfgarching\Core\Helpers\RouteRoles). Mehrere Rollen mit '|' trennen;
    | null = keine Rollenprüfung, nur 'auth'.
    |
    */

    'route_roles' => [
        'admin' => 'Administrator',
        'partner' => 'Administrator|Partner',
        'user' => null,
    ],

];
