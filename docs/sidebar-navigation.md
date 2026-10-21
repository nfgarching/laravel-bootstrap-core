# Sidebar-Navigation (SidebarNavigation-Registry)

Die linke Seitenleiste (`layout::parts.sidebar`) wird vom `core`-Package
bereitgestellt. Damit dieses Shell-Package **nicht** die Routen aller anderen
Feature-Packages kennen muss, tragen die Packages ihre Navigationsbereiche
selbst über eine zentrale Registry bei (Dependency Inversion).

> Das gleiche Muster gibt es für geteilte Modals
> ([ModalRegistry](modal-registry.md)) und Navbar-Items
> ([NavbarNavigation](navbar-navigation.md)).

## Problem (vorher)

Die `sidebar.blade.php` enthielt fest verdrahtete `@if (Route::has('…'))`-Blöcke
für jedes fremde Package (`documents.index`, `names.index`, `registers.index`,
`mailboxes`, …). Das `core`-Package war damit von jedem Domain-Package
abhängig – jede neue Route bedeutete eine Änderung in einem fremden Package, und
Einträge tauchten doppelt auf.

## Lösung: Registry

```text
Nfgarching\Core\Navigation\SidebarNavigation
```

Als **Singleton** im `LibraryServiceProvider::register()` gebunden (in
`register()`, damit jedes Package es in seinem `boot()` auflösen kann – `register()`
läuft für alle Provider vor allen `boot()`-Methoden).

### API

| Methode | Beschreibung |
| --- | --- |
| `section(string $view, int $order = 100, ?Closure $when = null): static` | Registriert einen Navigationsbereich. `$view` ist eine Blade-View, die `<li>`-Einträge rendert. `$order` bestimmt die Reihenfolge (kleiner = weiter oben). `$when` ist ein optionaler Sichtbarkeits-Guard; liefert er `false`, wird der Bereich ausgeblendet. |
| `visibleSections(): array<int, string>` | Liefert die nach `order` sortierten, per `when` gefilterten View-Namen. |

Die Sidebar iteriert nur noch:

```blade
<ul class="nav flex-column mb-2">
    @foreach (app(\Nfgarching\Core\Navigation\SidebarNavigation::class)->visibleSections() as $section)
        @includeIf($section)
    @endforeach
</ul>
```

## Einen Package-Nav hinzufügen

1. **Section-View im eigenen Package anlegen**, die `<li>`-Einträge rendert.
   Unterlinks mit eigenen `Route::has`-Guards absichern. Beispiel
   `names::navigation`:

   ```blade
   <li class="dropdown-item">
       <x-nav.link :href="route('names.index')">
           {{ __('Names') }}
       </x-nav.link>
   </li>
   ```

2. **Im `boot()` des Package-ServiceProviders registrieren:**

   ```php
   use Illuminate\Support\Facades\Route;
   use Nfgarching\Core\Navigation\SidebarNavigation;

   $this->app->make(SidebarNavigation::class)->section(
       'names::navigation',
       order: 30,
       when: fn (): bool => Route::has('names.index'),
   );
   ```

Am `core`-Package muss dafür **nichts** geändert werden.

## Aktuelle Bereiche

| Package | Section-View | order | sichtbar wenn (`when`) |
| --- | --- | --- | --- |
| documents | `documents::navigation` | 20 | `documents.index` |
| names | `names::navigation` | 30 | `names.index` |
| registers | `registers::navigation` | 40 | `registers.index` |
| claims | `core::nav-claims` | 50 | `claims.index` |
| files | `core::nav-files` | 60 | `files.index` |
| mails | `core::nav-mails` | 70 | `mailboxes` |
| textgenerator | `core::nav-textgenerator` | 80 | `text-compose.index` |
| chatbot | `core::nav-chatbot` | 90 | `chatbot.index` |

> Die `order`-Werte sind bewusst in 10er-Schritten vergeben, damit sich neue
> Bereiche dazwischen einsortieren lassen.

## Bewusst nicht in der Registry

Folgende Teile bleiben fest in `sidebar.blade.php`, weil sie **App-Shell** sind
und keine fachliche Package-Navigation:

- Dashboard- / Startpage-Kopf
- **„Controllers"** – ein paketübergreifendes Entwickler-Index-Menü („Grab-Bag":
  eine zusammengewürfelte Sammlung von Routen aus vielen Packages ohne
  gemeinsames fachliches Thema). Wäre der nächste Kandidat zum Zerlegen, falls
  jedes Package seinen Eintrag selbst in eine gemeinsame „Controllers"-Gruppe
  einhängen soll.
- **Docker** – Infrastruktur-Links (Portainer, Mailpit, …)
- **Testpages** und **Externe Links**
