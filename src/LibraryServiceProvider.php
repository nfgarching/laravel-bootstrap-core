<?php

namespace Nfgarching\Core;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nfgarching\Core\Livewire\Counter;
use Nfgarching\Core\Livewire\Modals\Field;
use Nfgarching\Core\Livewire\Modals\Multi;
use Nfgarching\Core\Livewire\Raw;
use Nfgarching\Core\Modals\ModalRegistry;
use Nfgarching\Core\Navigation\NavbarNavigation;
use Nfgarching\Core\Navigation\SidebarNavigation;
use Nfgarching\Core\Traits\PublishesPackageMigrations;

class LibraryServiceProvider extends ServiceProvider
{
    use PublishesPackageMigrations;

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $this->bootComponents();
        $this->bootLivewire();

        // Nur aus dem Package laden, wenn NICHT bereits ins Projekt publiziert.
        // Anonyme Migrationsklassen haben keinen Klassennamen, daher wird per
        // Dateiname geprüft, ob eine der Migrationen bereits publiziert wurde.
        if (! $this->migrationsArePublished(__DIR__.'/../database/migrations')) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'core-migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views/layouts', 'layout');
        $this->publishes([
            __DIR__.'/../resources/views/layouts' => resource_path('views/layouts'),
        ], 'nfgarching-layouts');

        $this->loadViewsFrom(__DIR__.'/../resources/views/pages', 'page');
        $this->publishes([
            __DIR__.'/../resources/views/pages' => resource_path('views/pages'),
        ], 'nfgarching-pages');

        $this->loadViewsFrom(__DIR__.'/../resources/views/pages/auth', 'auth');
    }

    private function bootComponents(): void
    {
        // Feature-Packages hängen ihre eigenen Komponenten-Verzeichnisse
        // ebenfalls an den Namespace 'core' an (loadViewsFrom(..., 'core')).
        $this->loadViewsFrom(__DIR__.'/../resources/views/components', 'core');

        // Alle Views sind damit ohne Einzelregistrierung als <x-core::...>
        // verfügbar (lazy aufgelöst).
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'core');

        // Bisherige Tag-Namen (<x-button>, <x-modal.save>, ...) deklariert jede
        // Komponente selbst im x-tags-Header ihrer Blade-Datei.
        $this->registerComponentTags();

        // Prüfen, ob die Klasse App\View\Components\AppLayout existiert
        if (! file_exists(app_path('View/Components/AppLayout.php'))) {
            if (file_exists(resource_path('views/layouts/main.blade.php'))) {
                Blade::component('layouts.main', 'app-layout');
            } else {
                Blade::component('layout::main', 'app-layout');
            }
        }

        // Prüfen, ob die Klasse App\View\Components\GuestLayout existiert
        if (! file_exists(app_path('View/Components/GuestLayout.php'))) {
            if (file_exists(resource_path('views/layouts/guest.blade.php'))) {
                Blade::component('layouts.guest', 'guest-layout');
            } else {
                Blade::component('layout::guest', 'guest-layout');
            }
        }

        // Check if layouts are published to application views, otherwise use package views
        if (! file_exists(app_path('View/Components/MainLayout.php'))) {
            if (file_exists(resource_path('views/layouts/main.blade.php'))) {
                Blade::component('layouts.main', 'main-layout');
            } else {
                Blade::component('layout::main', 'main-layout');
            }
        }

        // Check if layouts are published to application views, otherwise use package views
        if (! file_exists(app_path('View/Components/PopupLayout.php'))) {
            if (file_exists(resource_path('views/layouts/popup.blade.php'))) {
                Blade::component('layouts.popup', 'popup-layout');
            } else {
                Blade::component('layout::popup', 'popup-layout');
            }
        }
    }

    private function bootLivewire(): void
    {
        // Eigener Namespace statt 'livewire', damit Livewire dessen Views
        // (resources/views/vendor/livewire, höchste Priorität) das Partial nicht überschattet.
        $this->loadViewsFrom(__DIR__.'/../resources/views/livewire', 'core-livewire');

        Livewire::component('counter', Counter::class);
        Livewire::component('modal.field', Field::class);
        Livewire::component('modal.raw', Raw::class);
        Livewire::component('modal.multi', Multi::class);
    }

    /**
     * Register application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/app.php', 'app');

        // Central registry that feature packages use to contribute their own
        // sidebar navigation sections. Bound in register() so every package's
        // boot() can resolve it regardless of provider boot order.
        $this->app->singleton(SidebarNavigation::class);

        // Same pattern for shared modal partials: feature packages register
        // their own modals instead of the core package hardcoding them.
        $this->app->singleton(ModalRegistry::class);

        // Same pattern for navbar items: feature packages register their own
        // navbar buttons instead of the core package hardcoding them.
        $this->app->singleton(NavbarNavigation::class);
    }

    /**
     * Registriert die Tag-Aliase, die jede Komponenten-View selbst in ihrer
     * ersten Zeile deklariert, z.B. {{-- x-tags: button, button.link, link --}}.
     * Komponenten ohne Header sind nur über <x-core::...> erreichbar.
     */
    private function registerComponentTags(): void
    {
        $root = __DIR__.'/../resources/views/components';

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }

            $header = (string) file_get_contents($file->getPathname(), false, null, 0, 256);
            if (! preg_match('/\{\{--\s*x-tags:\s*(.+?)\s*--\}\}/', $header, $matches)) {
                continue;
            }

            // "field/wire/float/input.blade.php" → "core::field.wire.float.input"
            $relative = substr($file->getPathname(), strlen($root) + 1, -strlen('.blade.php'));
            $view = 'core::'.str_replace(DIRECTORY_SEPARATOR, '.', $relative);

            foreach (explode(',', $matches[1]) as $tag) {
                Blade::component($view, trim($tag));
            }
        }
    }
}
