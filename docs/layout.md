# Layout (App-Shell)

Das `core`-Package stellt die gemeinsame App-Shell bereit: das Grund-Layout
(`layout::layouts.main`) mit Navbar, einklappbarer Sidebar und Hauptbereich. Alle
Feature-Packages rendern ihren Inhalt in den `{{ $slot }}` dieses Layouts.

Verwandte Dokumente:
[Navbar-Navigation](navbar-navigation.md) ·
[Sidebar-Navigation](sidebar-navigation.md) ·
[ModalRegistry](modal-registry.md)

## Dateien

| View | Datei | Zweck |
| --- | --- | --- |
| `layout::layouts.main` | `resources/views/layouts/main.blade.php` | Grundgerüst (`<html>`, `<body>`, Grid aus Sidebar + Main) |
| `layout::parts.navbar` | `resources/views/layouts/parts/navbar.blade.php` | Obere Navigationsleiste |
| `layout::parts.sidebar` | `resources/views/layouts/parts/sidebar.blade.php` | Sidebar-Inhalt (siehe [Sidebar-Navigation](sidebar-navigation.md)) |
| `layout::parts.offcanvas` | `resources/views/layouts/parts/offcanvas.blade.php` | Offcanvas-Panel (Toggler rechts in der Navbar) |
| `layout::parts.head` / `.script` | … | `<head>`-Tags bzw. Skripte am `<body>`-Ende |

### Override-Pattern

Jeder `@include` prüft zuerst, ob die App eine eigene Variante unter
`resources/views/layouts/...` mitbringt, und fällt sonst auf die Package-View
(`layout::...`) zurück:

```blade
@if (file_exists(resource_path('views/layouts/parts/navbar.blade.php')))
    @include('layouts.parts.navbar')
@else
    @include('layout::parts.navbar')
@endif
```

So lässt sich jeder Teil in der App überschreiben, ohne das Package zu forken.

## Grid-Aufbau (`main.blade.php`)

```
<body x-data="{ sidebarOpen: window.innerWidth >= 992 }">
  └─ navbar
  └─ .container-fluid > .row
       ├─ <nav #sidebarMenu>  (col-md-3 col-lg-2)  – x-show="sidebarOpen"
       └─ <main>              (col-md-9 col-lg-10 | col-12)
```

Sidebar und Main liegen als zwei Bootstrap-Grid-Spalten nebeneinander in einer
`.row`.

## Einklappbare Sidebar (Alpine)

Der Sidebar-Zustand wird über eine einzige Alpine-Variable `sidebarOpen` am
`<body>` gehalten. Dadurch kann der Toggle-Button in der **Navbar** den Zustand
der Sidebar in `main.blade.php` steuern, obwohl beide in getrennten Includes
liegen (gemeinsamer Alpine-Scope ist der `<body>`).

| Element | Mechanik |
| --- | --- |
| `<body x-data="{ sidebarOpen: window.innerWidth >= 992 }">` | Init: ab `lg` (≥ 992 px) offen, darunter eingeklappt |
| `<nav #sidebarMenu x-show="sidebarOpen" x-cloak>` | Sidebar wird ein-/ausgeblendet |
| `<main :class="sidebarOpen ? 'col-md-9 col-lg-10' : 'col-12'">` | Main füllt bei eingeklappter Sidebar die volle Breite |
| Toggle-Button (Navbar): `@click="sidebarOpen = !sidebarOpen"` | schaltet um |

Der Button-Icon spiegelt den Zustand:

```blade
<i class="bi fs-6" :class="sidebarOpen ? 'bi-layout-sidebar' : 'bi-layout-sidebar-inset'"></i>
```

> **`x-cloak`**: verhindert ein kurzes Aufblitzen der Sidebar vor der
> Alpine-Initialisierung. Wirkt nur mit der CSS-Regel
> `[x-cloak] { display: none !important; }`.

## Navbar (`parts/navbar.blade.php`)

`navbar navbar-expand-lg` – die Navbar klappt unter dem `lg`-Breakpoint (992 px)
in den Hamburger-Toggler ein. Aufbau von links nach rechts:

| Bereich | Inhalt | Verhalten beim Einklappen |
| --- | --- | --- |
| Navbar-Toggler | Hamburger für die Navbar-Links (`#navbarNav`) | erscheint < 992 px |
| Sidebar-Toggler | Button mit `bi-layout-sidebar(-inset)` | immer sichtbar |
| Brand | `config('app.name')` | immer sichtbar |
| Collapse `#navbarNav` | Suchformular, Demo-Links, Package-Items (Registry) | klappt < 992 px ein |
| Rechte Gruppe | Offcanvas-Toggler (`bi-three-dots-vertical`) | **außerhalb** der Collapse → bleibt immer sichtbar (`ms-auto` = rechter Rand) |

Die Package-Buttons in der Collapse kommen aus der
[NavbarNavigation-Registry](navbar-navigation.md); der Offcanvas-Toggler bleibt
bewusst außerhalb des Collapse-Containers, damit er beim Togglen nicht
verschwindet.

### Breakpoint-Hinweis

Toggler und Collapse müssen denselben Breakpoint nutzen. Ein `d-md-none` am
Navbar-Toggler würde eine „tote Zone" (768–992 px) erzeugen, in der die Navbar
zwar eingeklappt, der Hamburger-Button aber noch versteckt ist. Der Toggler trägt
deshalb **keine** eigene `d-*`-Sichtbarkeitsklasse und folgt automatisch dem
`navbar-expand-lg`-Breakpoint.
