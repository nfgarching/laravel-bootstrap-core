<?php

namespace Nfgarching\Core\Helpers;

use Spatie\Permission\Middleware\RoleMiddleware;

/**
 * Rollen-Middleware für Package-Routen (spatie/laravel-permission).
 *
 * Die Rollen je Gruppe stehen in config('app.route_roles'), z.B.
 * 'admin' => 'Administrator', 'user' => 'Administrator|Mitarbeiter'.
 * Ohne spatie (oder ohne HasRoles am User-Model) oder ohne konfigurierte
 * Rollen bleibt es bei der bisherigen Middleware.
 */
class RouteRoles
{
    /**
     * Middleware-Liste für eine Gruppe: ['role:Rolle1|Rolle2'] oder [].
     */
    public static function middleware(string $group): array
    {
        $roles = config("app.route_roles.$group");

        if (blank($roles) || ! self::enabled()) {
            return [];
        }

        return ['role:'.implode('|', (array) $roles)];
    }

    /**
     * Ob spatie/laravel-permission installiert ist und das User-Model Rollen kennt.
     */
    public static function enabled(): bool
    {
        $userModel = config('auth.providers.users.model');

        return class_exists(RoleMiddleware::class)
            && is_string($userModel)
            && method_exists($userModel, 'hasAnyRole');
    }
}
