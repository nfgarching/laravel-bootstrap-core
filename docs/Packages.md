# Package-Architektur & Anleitung: Neues Package erstellen

Die Anwendung ist ein modulares Package-Monorepo: Die Fachdomänen sind als
eigenständige Laravel-Packages unter `packages/nfgarching/` organisiert.
Jedes Package ist **ein eigenes Git-Repository** und bringt seinen eigenen
Service Provider, Controller, Models, Livewire-Komponenten, Blade-Views,
Routen, Config und Migrationen mit.

## 1. Überblick über die Packages

| Package | Namespace | Domäne |
| --- | --- | --- |
| `calls` | `Nfgarching\Calls` | Telefonate / Interaktionen |
| `chatbot` | `Nfgarching\Chatbot` | KI-Chat (Anthropic/Gemini), Dataset-Extraktion |
| `claims` | `Nfgarching\Claims` | Forderungen |
| `core` | `Nfgarching\Core` | Gemeinsame Blade-/Livewire-Komponenten, Registries, Traits |
| `documents` | `Nfgarching\Documents` | Dokumentenverwaltung |
| `files` | `Nfgarching\Files` | Dateispeicherung und -abruf |
| `mails` | `Nfgarching\Mails` | E-Mail-Verwaltung |
| `names` | `Nfgarching\Names` | Kontakte, Adressen, Kommunikationseinträge |
| `registers` | `Nfgarching\Registers` | Akten / Register |
| `textbot`, `textgenerator` | `Nfgarching\Textbot`, `Nfgarching\Textgenerator` | Textgenerierung |

Das `core`-Package ist das **Fundament**: Es stellt die zentralen
Registries (Navigation, Modals), gemeinsame Traits und Basis-Komponenten
bereit, an die sich alle anderen Packages anschließen.

## 2. Anatomie eines Packages

Kanonische Verzeichnisstruktur (Beispiel `registers`):

```text
packages/nfgarching/<name>/
├── composer.json           # nur bei Composer-Einbindung (Variante A) relevant
├── config/<name>.php       # Package-Konfiguration
├── database/
│   └── migrations/         # eigene Migrationen (anonyme Klassen)
├── resources/
│   ├── views/              # Blade-Views (Namespace '<name>::')
│   └── dataset-definitions/# optional: XML/JSON-Definitionen für den Chatbot
├── routes/web.php          # Routen des Packages
├── src/
│   ├── <Name>ServiceProvider.php
│   ├── Controllers/
│   ├── Events/
│   ├── Listeners/
│   ├── Livewire/           # Livewire-Komponenten (Klassen)
│   ├── Models/
│   ├── Services/
│   └── Views/              # alternativ zu resources/views (so z.B. names)
├── tests/
├── README.md
└── CHANGELOG.md
```

Hinweis: Die Struktur variiert historisch leicht (z.B. hält `names` seine
Views unter `src/Views` statt `resources/views`). Für **neue Packages** gilt
die obige Struktur mit `resources/views`.

## 3. Einbindung ins Hauptprojekt – zwei Varianten

### Variante A: Composer-Path-Repository mit Package Discovery

So sind `chatbot`, `claims`, `core`, `documents`, `files`, `mails`,
`textbot` und `textgenerator` eingebunden:

- Eintrag in der Root-`composer.json` unter `require`:
  `"nfgarching/<name>": "@dev"`
- Eintrag unter `repositories`:

  ```json
  { "type": "path", "url": "packages/nfgarching/<name>", "options": { "symlink": true } }
  ```

- Das Package braucht eine **eigene, gültige `composer.json`** mit PSR-4-Autoload
  (`"Nfgarching\\<Name>\\": "src"`) und Laravel Package Discovery:

  ```json
  "extra": { "laravel": { "providers": ["Nfgarching\\<Name>\\<Name>ServiceProvider"] } }
  ```

- Aktivierung: `composer update nfgarching/<name>` (legt einen Symlink in
  `vendor/` an). Der Provider wird automatisch entdeckt – **kein** Eintrag in
  `bootstrap/providers.php` nötig (Doppelregistrierung vermeiden; sie ist
  harmlos, aber redundant – siehe `documents`, das derzeit beides hat).

### Variante B: Direktes Autoload im Root (leichtgewichtig)

So sind `calls`, `names` und `registers` eingebunden (für das nicht mehr
vorhandene `bookings` existiert noch ein verwaister Autoload-Eintrag):

- PSR-4-Eintrag direkt in der Root-`composer.json` unter `autoload`:

  ```json
  "Nfgarching\\<Name>\\": "packages/nfgarching/<name>/src/"
  ```

  danach `composer dump-autoload`.
- Provider **manuell** in `bootstrap/providers.php` registrieren:

  ```php
  Nfgarching\<Name>\<Name>ServiceProvider::class,
  ```

- Die `composer.json` im Package wird dabei nicht ausgewertet (sie darf
  existieren, ist aber nur Dokumentation für eine spätere Veröffentlichung).

### Empfehlung für neue Packages

**Variante B** ist der Standard für neue Packages (so auch in `CLAUDE.md`
dokumentiert): kein `composer update`, keine Symlinks, keine zu pflegende
Package-`composer.json`. Variante A lohnt sich erst, wenn ein Package
tatsächlich eigenständig (z.B. via Packagist/VCS) verteilt werden soll.

## 4. Schritt für Schritt: Neues Package erstellen

Beispiel: Package `invoices` mit Namespace `Nfgarching\Invoices`.

### 4.1 Git-Repository und Grundgerüst

```bash
mkdir -p packages/nfgarching/invoices/{src,config,routes,resources/views,database/migrations,tests}
cd packages/nfgarching/invoices
git init    # jedes Package ist ein eigenes Repo
```

`packages/nfgarching/.gitignore` enthält `*` – Package-Inhalte werden vom
Hauptrepo **ignoriert** und ausschließlich im Package-Repo versioniert.
Achtung bei der Suche mit Tools, die `.gitignore` respektieren: dort
`command grep` bzw. entsprechende Flags verwenden.

### 4.2 Autoload und Provider registrieren (Variante B)

Root-`composer.json`, Abschnitt `autoload.psr-4` ergänzen:

```json
"Nfgarching\\Invoices\\": "packages/nfgarching/invoices/src/"
```

Dann:

```bash
composer dump-autoload
```

`bootstrap/providers.php` ergänzen:

```php
Nfgarching\Invoices\InvoiceServiceProvider::class,
```

### 4.3 Service Provider anlegen

Das Grundmuster aller Provider im Projekt (vgl. `RegisterServiceProvider`,
`NameServiceProvider`):

```php
<?php

namespace Nfgarching\Invoices;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nfgarching\Core\Navigation\SidebarNavigation;
use Nfgarching\Core\Traits\PublishesPackageMigrations;

class InvoiceServiceProvider extends ServiceProvider
{
    use PublishesPackageMigrations;

    public function boot(): void
    {
        $this->bootComponents();
        $this->bootListeners();
        $this->bootLivewire();
        $this->bootChatbotIntegration();   // optional, siehe Abschnitt 6

        // Nur aus dem Package laden, wenn NICHT bereits ins Projekt publiziert.
        if (! $this->migrationsArePublished(__DIR__.'/../database/migrations')) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'invoices-migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'invoices');

        $this->app->make(SidebarNavigation::class)->section(
            'invoices::layouts.parts.sidebar',
            order: 40,
            when: fn (): bool => Route::has('invoices.index'),
        );

        $this->publishes([
            __DIR__.'/../config/invoices.php' => config_path('invoices.php'),
        ], 'invoices-config');
    }

    private function bootComponents(): void {}

    private function bootListeners(): void {}

    private function bootLivewire(): void
    {
        // Livewire::component('invoice.show.home', ...);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/invoices.php', 'invoices');
    }
}
```

**Faustregeln:**

- `register()`: nur Config-Merges und Container-Bindings (Singletons).
  Keine Zugriffe auf andere Packages – deren Bindings existieren evtl.
  noch nicht.
- `boot()`: Views, Routen, Migrationen, Livewire-/Blade-Registrierungen
  und **alle Beiträge an zentrale Registries** (Abschnitt 5/6). Zur
  Boot-Zeit sind alle `register()`-Phasen garantiert abgeschlossen –
  `$this->app->make(...)`-Aufrufe auf fremde Singletons sind sicher.
- Migrationen: immer über das Muster mit
  `PublishesPackageMigrations::migrationsArePublished()` laden, damit ins
  Projekt publizierte Migrationen nicht doppelt laufen.

### 4.4 Routen, Views, Livewire

- Routen in `routes/web.php`, benannt mit Package-Präfix
  (`invoices.index`, `invoices.show`, …). Benannte Routen sind wichtig,
  weil Navigation und Registries über `Route::has('invoices.index')`
  prüfen, ob das Package „aktiv" ist.
- Views über den Package-Namespace referenzieren: `invoices::pages.index`.
- Livewire-Komponenten in `boot()` explizit registrieren:
  `Livewire::component('invoices.listing', Index::class);`

### 4.5 Tests

- **Feature-/Integrationstests**, die das Zusammenspiel mit der App prüfen,
  gehören nach `tests/Feature/` im Hauptrepo (PHPUnit-Klassen, kein Pest).
- Eigene Package-Tests unter `packages/nfgarching/<name>/tests/` müssen als
  eigene Testsuite in der Root-`phpunit.xml` eingetragen werden (Vorbild:
  Testsuiten `Core`, `Textgenerator`, `Claims`).

## 5. Zentrale Registries (Extension Points)

Das Registry-Muster ist der Kern der Architektur: Ein Basis-Package bindet
in `register()` einen Singleton; Feature-Packages tragen in ihrem `boot()`
Einträge bei. Der Host rendert/verwendet die Einträge sortiert nach `order`
und gefiltert über die `when`-Closure.

| Registry | Package | Beitrag per | Zweck |
| --- | --- | --- | --- |
| `SidebarNavigation` | components | `->section($view, order:, when:)` | Sidebar-Abschnitt |
| `NavbarNavigation` | components | `->item($view, order:, when:)` | Navbar-Eintrag |
| `ModalRegistry` | components | `->modal($view, order:, when:)` | global eingebundene Modals |
| `DatasetTargetRegistry` | chatbot | `->target($key, $label, $handler, order:, when:)` | Speicherziele des Dataset-Modals |
| `DatasetDefinitionLoader` | chatbot | `->addPath($dir)` | XML/JSON-Struktur-Definitionen |

Beispiel (Sidebar):

```php
$this->app->make(SidebarNavigation::class)->section(
    'invoices::layouts.parts.sidebar',
    order: 40,
    when: fn (): bool => Route::has('invoices.index'),
);
```

Nützliche gemeinsame Traits aus `Nfgarching\Core\Traits`:
`PublishesPackageMigrations` (Migrations-Publishing), `ManagesDataset` /
`HasDataset` (Dataset-Anbindung), `ListingTrait`, `FormTrait`,
`ModalTrait`, `Searchable`.

## 6. Chatbot-Integration eines Packages

Ein Domain-Package, dessen Daten der Chatbot extrahieren und speichern
soll, registriert **alle drei Bausteine selbst** in einer Methode
`bootChatbotIntegration()` (Vorbild: `NameServiceProvider`,
`RegisterServiceProvider`):

```php
use Nfgarching\Chatbot\Datasets\DatasetResult;
use Nfgarching\Chatbot\Datasets\DatasetTargetRegistry;
use Nfgarching\Chatbot\Services\DatasetDefinitionLoader;

private function bootChatbotIntegration(): void
{
    // Guard: Package funktioniert auch ohne installiertes chatbot-Package.
    if (! class_exists(DatasetTargetRegistry::class)) {
        return;
    }

    // 1. Dataset-Model: erlaubt das Anhängen an Chat-Sessions.
    config(['chatbot.dataset_models' => array_merge(
        config('chatbot.dataset_models', []),
        [Invoice::class => ['label' => 'Rechnung', 'display' => 'nummer']],
    )]);

    // 2. Struktur-Definition (XML/JSON) für Extraktion und Dataset-Modal.
    $this->app->make(DatasetDefinitionLoader::class)
        ->addPath(__DIR__.'/../resources/dataset-definitions');

    $this->publishes([
        __DIR__.'/../resources/dataset-definitions' => resource_path('dataset-definitions'),
    ], 'invoices-dataset-definitions');

    // 3. Speicherziel: verarbeitet die extrahierten Daten (per Event).
    $this->app->make(DatasetTargetRegistry::class)->target(
        'invoice',
        'Rechnung',
        function (array $data, ?string $modelType, ?int $modelId): DatasetResult {
            $event = new NewInvoice($data, $modelType, $modelId);
            event($event);

            return DatasetResult::saved($event->invoiceId, $event->route, 'Rechnung angelegt.');
        },
        order: 30,
        when: fn (): bool => Route::has('invoices.index'),
    );
}
```

Dazu gehören:

- Eine XML-Definition `resources/dataset-definitions/invoice.xml` mit
  `<dataset key="invoice" label="…" target="invoice" tool="create_invoice">`
  und `<field>`-Kindern (Typen: `string`, `text`, `enum`, `object`,
  `array`; Vorbilder: `names/resources/dataset-definitions/name.xml`,
  `registers/resources/dataset-definitions/register.xml`). Das Format ist
  ausführlich in [Dataset-Definitionen.md](Dataset-Definitionen.md)
  dokumentiert.
- Ein Event (z.B. `NewInvoice`) plus Listener im eigenen Package
  (`Event::listen(NewInvoice::class, RecieveNewInvoice::class);` in
  `bootListeners()`), der die Daten tatsächlich persistiert.

**Präzedenzregel des Loaders:** Package-Pfade werden zuerst geladen, der
zentrale Config-Pfad (`chatbot.dataset_definitions_path`) zuletzt. Bei
gleichem `key` gewinnt also eine ins Projekt publizierte Definition –
Anpassungen pro Installation bleiben möglich, ohne das Package zu ändern.

Nur im `chatbot`-Package verbleiben die Legacy-Bestandteile
(`namen.xml`, Target `namen`, Legacy-Einträge `App\Models\Akte`,
`App\Models\Dokument`, `App\Models\Name` in `config/chatbot.php`) sowie die
generischen Targets `xml_download` und `textgenerator`.

## 7. Git-Workflow über alle Repos

```bash
./.gitpull.sh    # Hauptrepo + alle Package-Repos pullen
./.gitstatus.sh  # git status über alle Repos
```

- Commits erfolgen **pro Package-Repo** getrennt; eine Änderung, die
  mehrere Packages berührt (z.B. eine Registry-Erweiterung), ergibt
  mehrere Commits in mehreren Repos.
- Das Hauptrepo versioniert nur seine eigenen Dateien (u.a.
  `composer.json`, `bootstrap/providers.php`, `tests/`) – die
  Package-Verzeichnisse sind per `.gitignore` ausgeblendet.

## 8. Checkliste: Neues Package

1. [ ] Verzeichnis `packages/nfgarching/<name>/` mit Grundstruktur anlegen, `git init`
2. [ ] PSR-4-Autoload in Root-`composer.json` + `composer dump-autoload` (Variante B)
3. [ ] `<Name>ServiceProvider` anlegen, in `bootstrap/providers.php` eintragen
4. [ ] `config/<name>.php` + `mergeConfigFrom()` in `register()`
5. [ ] Routen (benannt, mit Package-Präfix), Views (`<name>::`), Livewire-Komponenten
6. [ ] Migrationen mit `PublishesPackageMigrations`-Muster + Publish-Tag `<name>-migrations`
7. [ ] Navigation beitragen (`SidebarNavigation`/`NavbarNavigation`, `when: Route::has(...)`)
8. [ ] Optional: Chatbot-Integration (`bootChatbotIntegration()`, Abschnitt 6)
9. [ ] Feature-Tests in `tests/Feature/`; ggf. Testsuite in `phpunit.xml`
10. [ ] `vendor/bin/pint --dirty` und `php artisan test --compact` laufen lassen
11. [ ] README.md im Package; Eintrag in der Package-Tabelle in `CLAUDE.md` ergänzen
