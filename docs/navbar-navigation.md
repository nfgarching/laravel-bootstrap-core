# Navbar-Items (NavbarNavigation-Registry)

Die obere Navigationsleiste (`layout::parts.navbar`) wird vom `core`-Package
bereitgestellt. Damit dieses Shell-Package **nicht** die Buttons (und Routen)
aller anderen Feature-Packages kennen muss, tragen die Packages ihre Navbar-Items
selbst über eine zentrale Registry bei (Dependency Inversion).

Der Mechanismus ist das exakte Gegenstück zur
[Sidebar-Navigation](sidebar-navigation.md) und zur
[ModalRegistry](modal-registry.md).

## Problem (vorher)

Die `navbar.blade.php` enthielt fest verdrahtete Feature-Buttons – das
Datei-Modal (`openModalFiles`), den Sipgate-Anruf (`openModalSipcall`) sowie die
Mail-Buttons (`openModalMailbox`, `openModalEnvelope`). Das `core`-Package
war damit von `files`, `calls` und `mails` abhängig; jeder neue Package-Button
bedeutete eine Änderung im Shell-Package.

## Lösung: Registry

```text
Nfgarching\Core\Navigation\NavbarNavigation
```

Als **Singleton** im `LibraryServiceProvider::register()` gebunden (in
`register()`, damit jedes Package es in seinem `boot()` auflösen kann –
`register()` läuft für alle Provider vor allen `boot()`-Methoden).

### API

| Methode | Beschreibung |
| --- | --- |
| `item(string $view, int $order = 100, ?Closure $when = null): static` | Registriert ein Navbar-Item. `$view` ist eine Blade-View, die einen oder mehrere Buttons rendert. `$order` bestimmt die Reihenfolge (kleiner = weiter links). `$when` ist ein optionaler Sichtbarkeits-Guard; liefert er `false`, wird das Item ausgeblendet. |
| `visibleItems(): array<int, string>` | Liefert die nach `order` sortierten, per `when` gefilterten View-Namen. |

Die Navbar iteriert nur noch:

```blade
@foreach (app(\Nfgarching\Core\Navigation\NavbarNavigation::class)->visibleItems() as $item)
    @includeIf($item)
@endforeach
```

## Ein Package-Navbar-Item hinzufügen

1. **Item-View im eigenen Package anlegen**, die die Buttons rendert. Beispiel
   `core::navbar-files`:

   ```blade
   <x-button.dispatch modalName="openModalFiles" class="navbar-toggler">
       <span class="bi bi-folder"></span>
   </x-button.dispatch>
   ```

2. **Im `boot()` des Package-ServiceProviders registrieren:**

   ```php
   use Illuminate\Support\Facades\Route;
   use Nfgarching\Core\Navigation\NavbarNavigation;

   $this->app->make(NavbarNavigation::class)->item(
       'core::navbar-files',
       order: 60,
       when: fn (): bool => Route::has('files.index'),
   );
   ```

Am `core`-Package muss dafür **nichts** geändert werden.

## Aktuelle Items

| Package | Item-View | order | sichtbar wenn (`when`) |
| --- | --- | --- | --- |
| chatbot | `core::navbar-chatbot` | 10 | `chatbot.index` |
| files | `core::navbar-files` | 60 | `files.index` |
| calls | `calls::navbar` | 65 | `sipcalls.index` |
| mails | `core::navbar-mails` | 70 | `mailboxes` |

> Die `order`-Werte sind – wie bei der Sidebar – bewusst gestaffelt, damit sich
> neue Items dazwischen einsortieren lassen.

## Bewusst nicht in der Registry

Folgende Teile bleiben fest in `navbar.blade.php`, weil sie **App-Shell** sind
und keine fachliche Package-Funktion:

- Navbar-Brand, Sidebar-Toggler und das Suchformular
- Die Demo-Links (`Link #1`–`#4`)
- Der Offcanvas-Toggler samt `@include(... offcanvas)`
