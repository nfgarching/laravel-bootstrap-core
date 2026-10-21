# Geteilte Modals (ModalRegistry)

Die globalen Modals werden über den Aggregator `layout::parts.modals` (im
`core`-Package) ins Layout gerendert – einmal pro Seite via
`layouts/parts/script.blade.php`. Damit dieses Shell-Package **nicht** die
konkreten Modals aller Feature-Packages kennen muss, tragen die Packages ihre
Modal-Partials selbst über eine zentrale Registry bei (Dependency Inversion).

Der Mechanismus ist das exakte Gegenstück zur
[Sidebar-Navigation](sidebar-navigation.md) und zur
[NavbarNavigation](navbar-navigation.md).

## Problem (vorher)

Die `modals.blade.php` band das Datei-Modal fest per
`@include('layout::parts.modalFile')` ein, obwohl `modalFile.blade.php`
(das `#sharedAttachModal` samt JS) rein zur `files`-Funktionalität gehört. Das
`core`-Package kannte damit ein fremdes Feature-Modal – jedes weitere
Package-Modal hätte einen weiteren festen `@include` im Shell-Package bedeutet.

## Lösung: Registry

```text
Nfgarching\Core\Modals\ModalRegistry
```

Als **Singleton** im `LibraryServiceProvider::register()` gebunden (in
`register()`, damit jedes Package es in seinem `boot()` auflösen kann –
`register()` läuft für alle Provider vor allen `boot()`-Methoden).

### API

| Methode | Beschreibung |
| --- | --- |
| `modal(string $view, int $order = 100, ?Closure $when = null): static` | Registriert ein Modal-Partial. `$view` ist eine Blade-View, die ein oder mehrere Modals rendert. `$order` bestimmt die Reihenfolge (kleiner = zuerst). `$when` ist ein optionaler Sichtbarkeits-Guard; liefert er `false`, wird das Modal nicht gerendert. |
| `visibleModals(): array<int, string>` | Liefert die nach `order` sortierten, per `when` gefilterten View-Namen. |

Der Aggregator `layout::parts.modals` iteriert nur noch:

```blade
@foreach (app(\Nfgarching\Core\Modals\ModalRegistry::class)->visibleModals() as $modal)
    @includeIf($modal)
@endforeach
```

## Ein Package-Modal hinzufügen

1. **Modal-View im eigenen Package anlegen**, die das Modal-Markup (und ggf.
   zugehöriges JS) rendert. Dazu einen View-Namespace registrieren, falls noch
   keiner auf das Verzeichnis zeigt. Beispiel `files-layout::parts.modals`:

   ```php
   $this->loadViewsFrom(__DIR__.'/../resources/views/layouts', 'files-layout');
   ```

2. **Im `boot()` des Package-ServiceProviders registrieren:**

   ```php
   use Illuminate\Support\Facades\Route;
   use Nfgarching\Core\Modals\ModalRegistry;

   $this->app->make(ModalRegistry::class)->modal(
       'files-layout::parts.modals',
       order: 60,
       when: fn (): bool => Route::has('files.index'),
   );
   ```

Am `core`-Package muss dafür **nichts** geändert werden.

## Aktuelle Modals

| Package | Modal-View | order | sichtbar wenn (`when`) |
| --- | --- | --- | --- |
| files | `files-layout::parts.modals` | 60 | `files.index` |

> Die `order`-Werte werden – wie bei der Sidebar – bewusst in 10er-Schritten
> vergeben, damit sich neue Modals dazwischen einsortieren lassen.

## Bewusst nicht in der Registry

Folgende Modals bleiben fest im Aggregator `layout::parts.modals`, weil sie
**App-Shell** sind und keine fachlichen Package-Modals:

- `@stack('modals')` – seitenlokale Modals, die einzelne Views per `@push`
  beitragen.
- Generische Komponenten-Modals (`<livewire:modal.field />`,
  `<livewire:modal.raw />`, `<x-modal.msg id="modalConfirm" />`).
- Die globalen JS-Helfer (`show-modal`/`hide-modal`-Listener, `closeAllModals()`).
