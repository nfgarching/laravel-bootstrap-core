# Laravel Bootstrap Core (`nfgarching/core`)

Basis-Package für Laravel-Anwendungen mit **Bootstrap 5** und **Livewire 4**:
wiederverwendbare Blade-Komponenten, App-Layouts (Navbar, Sidebar, Modals,
Toasts), Login/Registrierung, Registries für Navigation und Modals, ein
polymorphes `Prop`-Model mit CRUD sowie Traits für Listen, Formulare und Suche.

> **Status:** Das Package ist aus einer konkreten Anwendung heraus entstanden
> und wird dort weiterentwickelt. Es gibt noch keine getaggten Releases, die
> API kann sich ändern. Einzelne Layouts und Seiten verlinken auf Routen der
> Anwendung (z. B. in der Sidebar) und sind als Ausgangspunkt zum Publizieren
> und Anpassen gedacht.

## Anforderungen

- PHP 8.4
- Laravel 13
- Livewire 4
- Bootstrap 5.3 und Bootstrap Icons (werden von der Anwendung per Vite geladen)
- `kyslik/column-sortable` (wird als Abhängigkeit mitinstalliert)

## Installation

### Aus GitHub (VCS-Repository)

In der `composer.json` der Anwendung:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/nfgarching/laravel-bootstrap-core"
    }
]
```

```bash
composer require nfgarching/core:dev-main
```

### Lokal als Path-Repository (Entwicklung)

```bash
git clone https://github.com/nfgarching/laravel-bootstrap-core.git packages/nfgarching/core
```

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/nfgarching/core",
        "options": { "symlink": true }
    }
]
```

```bash
composer require nfgarching/core:@dev
```

Composer legt einen Symlink unter `vendor/nfgarching/core` an.

In beiden Fällen wird der `LibraryServiceProvider` über Laravels
Package-Discovery (`extra.laravel.providers`) automatisch registriert – ein
Eintrag in `bootstrap/providers.php` ist nicht nötig.

> Die Anwendung nutzt üblicherweise `minimum-stability: stable`, das Package
> läuft auf `dev-main`. Deshalb wird mit `dev-main` bzw. dem Stability-Flag
> `@dev` required.

**Migrationen:** Das Package bringt derzeit keine aktiven Migrationen mit (die
Dateien unter `database/migrations/*.todo` sind Entwürfe). Die Tabellen `users`
und `props` muss die Anwendung selbst anlegen.

---

## Architektur

```text
core/
├── composer.json
├── config/app.php                   Konfiguration (start_page, version)
├── docs/                            Detail-Dokumentation
├── routes/
│   ├── web.php                      Routen
│   └── settings.php                 Auth-/Settings-Routen
├── database/
│   ├── factories/                   UserFactory
│   └── migrations/                  Migrations-Entwürfe (*.todo, nicht aktiv)
├── resources/
│   └── views/
│       ├── components/              Blade-Komponenten (Namespace core::)
│       ├── layouts/                 main, guest, popup (Namespace layout::)
│       ├── livewire/                Livewire-Partials (Namespace core-livewire::)
│       └── pages/                   auth, props, dashboard, admin, ... (Namespace page::)
├── src/
│   ├── LibraryServiceProvider.php
│   ├── Controllers/                 PropController, Auth/
│   ├── Database/                    PropFactory, PropsSeeder
│   ├── Helpers/                     Config, DateTime, Paginate
│   ├── Livewire/                    Counter, Raw, Modals/Field, Modals/Multi
│   ├── Modals/ModalRegistry.php
│   ├── Models/                      Prop, DatasetEntry
│   ├── Navigation/                  NavbarNavigation, SidebarNavigation
│   ├── Services/Verlauf.php
│   └── Traits/
└── tests/                           Feature- und Unit-Tests (laufen in der Host-Anwendung)
```

---

## Routen

| Methode | URI | Name | Middleware |
| --- | --- | --- | --- |
| GET | `/welcome` | `welcome` | – |
| GET | `/login` | `login` | guest |
| POST | `/login` | – | guest |
| GET | `/register` | `register` | guest |
| POST | `/register` | – | guest |
| GET/POST | `/logout` | `logout` | web |
| GET | `/dashboard` | `dashboard` | web |
| GET | `/startpage` | `startpage` | web |
| GET | `/admin` | `admin` | web |
| GET | `/guestpage` | `guestpage` | guest |
| GET | `/testpage` | `testpage` | web |
| GET | `/testform` | `testform` | web |
| GET | `/testing` | `testing` | web |
| GET | `/raw` | `raw` | auth |
| GET | `/props` | `core.props.index` | auth |
| GET | `/props/create` | `core.props.create` | auth |
| POST | `/props` | `core.props.store` | auth |
| GET | `/props/{prop}` | `core.props.show` | auth |
| GET | `/props/{prop}/edit` | `core.props.edit` | auth |
| PUT | `/props/{prop}` | `core.props.update` | auth |
| DELETE | `/props/{prop}` | `core.props.destroy` | auth |
| GET | `/props/{id}/action` | `core.props.action` | auth |

---

## Prop-Model

Polymorphes Model – kann an jeden anderen Eloquent-Model-Typ gehängt werden.

**Tabelle:** `props`

| Spalte | Typ | Beschreibung |
| --- | --- | --- |
| `id` | bigint | Primary Key |
| `model_type` | string | Polymorphic Typ (z. B. `App\Models\Dokument`) |
| `model_id` | bigint | Polymorphic ID |
| `key` | string\|null | Eigenschaftsschlüssel |
| `state` | string\|null | Status: `active`, `error`, `verified`, `unverified`, `pending` |
| `type` | string\|null | Typ: `birth_date`, `death_date`, `iban`, `metadata` |
| `label` | string\|null | Anzeigebezeichnung |
| `value` | string\|null | Eigentlicher Wert |
| `data` | json\|null | Strukturierte Zusatzdaten (wird als Array gecastet) |
| `memo` | text\|null | Freitext-Notiz |

**Traits:** `HasFactory`, `Searchable` (key, label, value, memo), `Sortable` (id, state, type)

**Beziehung:** `model()` → `MorphTo`

**Verwendung in anderen Models:**

```php
public function props(): MorphMany
{
    return $this->morphMany(\Nfgarching\Core\Models\Prop::class, 'model');
}
```

---

## PropController

**Namespace:** `Nfgarching\Core\Controllers\PropController`

| Methode | Beschreibung |
| --- | --- |
| `index()` | Liste mit Suche, Filter (state, type, key, label), Sortierung, Pagination (8/Seite) |
| `create()` | Formular für neuen Prop |
| `store()` | Erstellt neuen Prop **oder** Bulk-Update via `switchTo=change` |
| `show()` | Detailansicht |
| `edit()` | Bearbeitungsformular |
| `update()` | Speichert Änderungen, leitet zu `show` weiter |
| `destroy()` | Stub (dd) |
| `action()` | Scaffold für spezifische Aktionen (Stub) |

**Bulk-Operation (`store` mit `switchTo=change`):**

```html
<form method="POST" action="{{ route('core.props.store') }}">
    <input type="hidden" name="switchTo" value="change">
    <input type="checkbox" name="checks[]" value="{{ $prop->id }}">
    <select name="state">...</select>
</form>
```

---

## Blade-Komponenten

### Registrierung (x-tags-Header)

Es gibt **keine zentrale Registrierungsliste** im Service Provider. Stattdessen:

1. **Namespace-Syntax, automatisch:** `resources/views/components/` ist per
   `Blade::anonymousComponentPath()` registriert. Jede Blade-Datei ist damit
   ohne weitere Registrierung als `<x-core::modal.save />` verfügbar.

2. **Kurze Tag-Namen, per Header:** Jede Komponente deklariert ihre Tag-Aliase
   selbst in der ersten Zeile ihrer Blade-Datei (kommagetrennt bei mehreren):

   ```blade
   {{-- x-tags: button, button.click, button.link, link --}}
   ```

   `LibraryServiceProvider::registerComponentTags()` liest diese Header beim
   Boot ein und registriert die Aliase per `Blade::component()`. Komponenten
   ohne Header sind nur über die Namespace-Syntax erreichbar.

**Neue Komponente:** Blade-Datei ins Verzeichnis legen, bei Bedarf
`x-tags`-Header in die erste Zeile schreiben – fertig.

Details und eine generierte Tabelle aller Tag-Aliase:
[docs/komponenten-registrierung.md](docs/komponenten-registrierung.md)

### Layout

```blade
<x-app-layout>
    <x-slot name="header">...</x-slot>
    ...
</x-app-layout>
```

### Card

```blade
<x-card title="Titel">
    <x-slot name="headerLeft">...</x-slot>
    <x-slot name="headerRight">...</x-slot>
    Inhalt
    <x-slot name="footer">...</x-slot>
</x-card>
```

### Tabelle mit Filter und Collapse

```blade
<x-form :action="route('...')">
    @php
        $colspan = 5;
        $token = rand();
        $toggleCollapse = true;
        $toggleChecks = true;
        $toggleRadios = false;
    @endphp

    <x-table :acc_id="$token" class="table-sm" :$colspan :$toggleCollapse :$toggleChecks :$toggleRadios>
        @forelse ($items as $item)
            @if ($loop->first)
                <x-slot:header>
                    <x-table.head.sort field="state" />
                    <x-table.head.sort field="type" />
                </x-slot:header>
            @endif

            <x-table.row :acc_id="$token" :row_id="$item->id" :$toggleCollapse :$toggleChecks :$toggleRadios>
                <td>{{ $item->state }}</td>
                <td>{{ $item->type }}</td>

                <x-slot:row2>
                    <td colspan="{{ $colspan - 1 }}">
                        <x-desc.list :item="$item->toArray()" />
                    </td>
                </x-slot:row2>
            </x-table.row>
        @empty
            <tr><td colspan="{{ $colspan }}">Keine Daten</td></tr>
        @endforelse

        <x-slot:footer>
            <button type="submit" name="switchTo" value="change">Ändern</button>
        </x-slot:footer>
    </x-table>
</x-form>
```

### Filter-Leiste

```blade
<x-table.filter>
    <x-table.filter.cnt_per_page route="core.props.index" :request="$request" />
    <x-table.filter.search field="search" :oldval="$request->search" />
    <x-table.filter.select field="state" :oldval="$request->state"
        :options="['active', 'error', 'verified', 'unverified', 'pending']" />
</x-table.filter>
```

### Buttons

```blade
<x-button type="primary">Text</x-button>
<x-button.link :href="route('...')" type="outline-secondary" size="sm">
    <i class="bi bi-pencil-square"></i>
</x-button.link>
<x-button.group>
    <x-button.clipboard :text="$item->name" />
</x-button.group>
```

### Formular-Felder

```blade
<x-field.group>
    <div class="col-sm-6">
        <x-field field="key" :value="old('key', $prop->key ?? '')" />
    </div>
    <div class="col-sm-6">
        <x-field.select field="state" :value="old('state', $prop->state ?? '')">
            <option value="active">active</option>
        </x-field.select>
    </div>
    <div class="col-sm-12">
        <x-field.text field="memo" :value="old('memo', $prop->memo ?? '')" />
    </div>
</x-field.group>
```

### Navigation / Tabs

```blade
@php
    $show = 1;
    $tabs = ['Übersicht', 'Details', 'Test'];
    $token = rand();
@endphp
<x-nav.tabs :$tabs :$token :$show>
    <x-nav.item tab="0" :$token :$show>
        Inhalt Tab 1
    </x-nav.item>
    <x-nav.item tab="1" :$token :$show>
        Inhalt Tab 2
    </x-nav.item>
</x-nav.tabs>
```

### Modal

```blade
<x-modal id="myModal" title="Titel">
    Inhalt
    <x-slot:footer>
        <x-modal.save />
    </x-slot:footer>
</x-modal>

<x-modal.button target="myModal">Öffnen</x-modal.button>
```

### Daten anzeigen

```blade
{{-- Key-Value-Liste aus Array --}}
<x-desc.list :item="$prop->toArray()" />

{{-- Einzelner Wert --}}
<x-desc label="State">{{ $prop->state }}</x-desc>

{{-- Model-Link (Button mit Link zum übergeordneten Model) --}}
<x-button.model :model="$prop->model_type" :modid="$prop->model_id" />
```

---

## Livewire-Komponenten

### Counter

Einfacher Zähler, lauscht auf das `testing`-Event.

```blade
<livewire:counter />
{{-- oder --}}
<x-counter startTime="{{ $startTime }}" />
```

### Wire (Raw Modal)

Generischer Daten-Viewer für beliebige Eloquent-Models. Öffnet ein Modal mit allen Attributen.

```blade
{{-- Öffnen via Livewire-Event --}}
$this->dispatch('openModalRaw', model: \App\Models\Dokument::class, id: $id);
```

---

## Traits

### Searchable

Fügt einem Eloquent-Model eine `search()`-Scope-Methode hinzu.

```php
// Im Model:
use Nfgarching\Core\Traits\Searchable;

public $searchable = ['key', 'label', 'value', 'memo'];

// Im Controller:
Model::search($request->search)->where($where)->get();
```

### MainControllerTrait

Wiederverwendbare Query-Logik für Controller.

```php
use Nfgarching\Core\Traits\MainControllerTrait;

// Gibt paginierte, gefilterte, sortierte Ergebnisse zurück:
$rows = $this->get_rows(Model::query(), $request);

// Navigation (erste/vorige/nächste/letzte):
$navigation = $this->getNavigationData($model, $request, Model::class);
```

### FormTrait / ModalTrait / ListingTrait

Basis-Traits für Livewire-Komponenten. Bieten standardisierte Methoden für:

- `FormTrait`: Felder per Livewire-Event aktualisieren
- `ModalTrait`: Modal öffnen/schließen, insert/update/delete/select
- `ListingTrait`: Paginierung, Suche, Sortierung, Filter für Listen-Komponenten

---

## Sidebar-Navigation

Die Seitenleiste (`layout::parts.sidebar`) lädt Package-Navigationen nicht mehr
hardcodiert – jedes Feature-Package trägt seinen Bereich selbst über die
`SidebarNavigation`-Registry bei. Konzept und Anleitung:
[docs/sidebar-navigation.md](docs/sidebar-navigation.md).

---

## Konfiguration

`config/app.php`:

```php
return [
    'start_page' => 'page::dashboard',
    'version'    => '1.0.0',
];
```

---

## Tests

Die Tests nutzen die Test-Umgebung der Host-Anwendung (`App\Models\User`,
Datenbank, Layouts). Dazu das Verzeichnis `vendor/nfgarching/core/tests` als
Testsuite in die `phpunit.xml` der Anwendung aufnehmen, z. B.:

```xml
<testsuite name="Core">
    <directory>vendor/nfgarching/core/tests</directory>
</testsuite>
```

```bash
php artisan test --compact --testsuite=Core
php artisan test --compact --filter=PropControllerTest
```

**PropControllerTest** (`tests/Feature/PropControllerTest.php`) – 19 Tests:

- Auth-Schutz auf allen Routen
- Index: View, Filterung nach `state`/`type`, Pagination
- Create/Store: Formular, Anlegen, Redirect
- Store Bulk: `state` und `type` für mehrere Props ändern
- Show/Edit/Update: Views, Route Model Binding, Datenpersistenz

---

## Bekannte Hinweise

**Test- und Demo-Routen** – `/testpage`, `/testform`, `/testing`, `/welcome`
und `/greeting_from_bootstrap` werden ohne Login registriert. In produktiven
Anwendungen gegebenenfalls über eigene Routen überschreiben.

**View-Überschreibung** – Layouts können in die Anwendung publiziert und überschrieben werden:

```bash
php artisan vendor:publish --provider="Nfgarching\Core\LibraryServiceProvider" --tag=nfgarching-layouts
```

---

## Lizenz

MIT – siehe [LICENSE](LICENSE).
