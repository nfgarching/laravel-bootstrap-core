<?php

namespace Nfgarching\Core\Traits;

trait PublishesPackageMigrations
{
    /**
     * Prüft, ob die Package-Migrationen bereits ins Projekt publiziert wurden.
     *
     * Da Laravel-Migrationen anonyme Klassen ohne Namen sind, wird anhand des
     * Dateinamen-Suffixes geprüft, ob eine entsprechende Migration bereits unter
     * database/migrations/ existiert.
     */
    protected function migrationsArePublished(string $migrationsPath): bool
    {
        foreach (glob($migrationsPath.'/*.php') as $migration) {
            $suffix = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', basename($migration));

            if (! empty(glob(database_path('migrations/*_'.$suffix)))) {
                return true;
            }
        }

        return false;
    }
}
