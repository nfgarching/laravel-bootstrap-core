# Registrierung der Blade-Komponenten

Das Package registriert seine Blade-Komponenten **nicht** über eine zentrale
Liste im Service Provider, sondern über zwei Mechanismen, die in
`LibraryServiceProvider::bootComponents()` aktiviert werden.

## 1. Anonymous Component Paths (Namespace-Syntax)

Das Komponenten-Verzeichnis ist per `Blade::anonymousComponentPath()`
registriert:

| Verzeichnis | Prefix | Beispiel-Tag |
| --- | --- | --- |
| `resources/views/components/` | `core` | `<x-core::modal.save />` |

Damit ist **jede** Blade-Datei dieses Verzeichnisses sofort als Komponente
verfügbar – ohne Registrierung, auch neu hinzugefügte. Die Auflösung erfolgt
lazy beim Kompilieren der View und wird im View-Cache eingefroren; zur
Laufzeit entstehen keine Kosten.

### Wie `anonymousComponentPath()` intern funktioniert

**Bei der Registrierung** passiert fast nichts: Laravel merkt sich nur Pfad
und Prefix und registriert den Pfad als View-Namespace unter einem
**xxh128-Hash** des Prefix (`BladeCompiler::anonymousComponentPath()`).
Dieser interne Hash-Namespace kollidiert nie mit echten View-Namespaces –
deshalb kann `core` gleichzeitig Prefix hier und View-Namespace von
`loadViewsFrom()` sein. Es wird **nichts gescannt**.

**Beim Kompilieren** prüft der `ComponentTagCompiler` für jedes `<x-…>`-Tag
der Reihe nach:

1. **Explizite Aliase** aus `Blade::component()` – das sind die
   `x-tags`-Header dieses Packages. Sie gewinnen immer.
2. Klassen-Komponenten (registrierte Namespaces, dann Klassennamen-Raten
   in `App\View\Components\…`).
3. **Anonymous Component Paths** – erst hier kommt das Verzeichnis ins Spiel:
   Beginnt das Tag mit `core::`, wird der Rest im Hash-Namespace in
   drei Varianten gesucht:

   | Versuch | Datei für `<x-core::modal>` |
   | --- | --- |
   | direkt | `modal.blade.php` |
   | `.index`-Konvention | `modal/index.blade.php` |
   | Verzeichnis = Name | `modal/modal.blade.php` |

   Deshalb funktioniert `<x-core::modal>` für `modal/index.blade.php`,
   ohne dass „index" im Tag steht.

Die Auflösung läuft nur **einmal beim Kompilieren**; das Ergebnis landet als
fertiger PHP-Code im View-Cache (`storage/framework/views/`). Die
`file_exists`-Prüfungen fallen also nur nach `view:clear` oder bei
geänderten Dateien an.

### Abgrenzung zu den Alternativen

| Mechanismus | Wirkung |
| --- | --- |
| `Blade::anonymousComponentPath($path, $prefix)` | Ganzes Verzeichnis als `<x-prefix::…>`-Tags, lazy |
| `Blade::component($view, $alias)` | Genau *ein* Alias auf genau *eine* View (→ `x-tags`-Header) |
| `loadViewsFrom($path, $ns)` | Nur View-Namespace für `view('ns::…')` / `@include` – löst keine Tags auf |
| `Blade::componentNamespace($ns, $prefix)` | Gegenstück für *klassenbasierte* Komponenten (hier nicht genutzt) |

Hinweis: Ohne Prefix (`anonymousComponentPath($path)`) wären die Komponenten
*unprefixed* verfügbar (`<x-modal.save>`), würden dann aber mit den
App-eigenen Komponenten in `resources/views/components/` der Anwendung
konkurrieren. Mit Prefix ist sauber getrennt, was aus dem Package kommt
und was aus der App.

## 2. Tag-Aliase über den `x-tags`-Header

Die kurzen, historisch gewachsenen Tag-Namen (`<x-button>`, `<x-modal.save>`,
`<x-wire.input>`, …) deklariert **jede Komponente selbst** in der ersten Zeile
ihrer Blade-Datei:

```blade
{{-- x-tags: button, button.click, button.link, link --}}
```

`LibraryServiceProvider::registerComponentTags()` durchläuft beim Boot beide
Verzeichnisse, liest diese Header und registriert jeden Eintrag per
`Blade::component($view, $tag)`. Mehrere Aliase werden kommagetrennt
angegeben (im Beispiel: `<x-button>`, `<x-button.click>`, `<x-button.link>`
und `<x-link>` zeigen alle auf `core::button.index`).

Regeln:

- Der Header muss innerhalb der **ersten 256 Bytes** der Datei stehen
  (Konvention: erste Zeile).
- Komponenten **ohne** Header sind nur über die Namespace-Syntax
  (`<x-core::…>`) erreichbar.
- Explizit registrierte Aliase haben bei der Tag-Auflösung **Vorrang** vor
  automatisch aufgelösten Komponenten der Anwendung
  (`resources/views/components/` der App).

### Neue Komponente anlegen

1. Blade-Datei in eines der beiden Verzeichnisse legen – fertig
   (erreichbar als `<x-core::pfad.name>`).
2. Soll sie zusätzlich einen kurzen Tag-Namen bekommen:
   `{{-- x-tags: mein.tag --}}` als erste Zeile eintragen.

### Performance

Der Header-Scan kostet beim Boot ca. **1,3 ms pro Request** (gemessen bei
~90 Dateien / 80 Tags). Sollte das Package deutlich wachsen, kann das
Ergebnis des Scans gecacht werden (z. B. Bootstrap-Cache); aktuell lohnt
sich das nicht.

## Registrierte Tag-Aliase

Diese Tabelle ist aus den `x-tags`-Headern generiert (Stand: 2026-06-12).
Maßgeblich sind immer die Header in den Blade-Dateien selbst.

| Tag | View |
| --- | --- |
| `<x-accordion>` | `core::accordion.index` |
| `<x-accordion.item>` | `core::accordion.item` |
| `<x-button>` | `core::button.index` |
| `<x-button.cancel>` | `core::button.cancel` |
| `<x-button.click>` | `core::button.index` |
| `<x-button.clipboard>` | `core::button.clipboard` |
| `<x-button.dispatch>` | `core::button.dispatch` |
| `<x-button.group>` | `core::button.group` |
| `<x-button.link>` | `core::button.index` |
| `<x-button.model>` | `core::button.model` |
| `<x-button.openModalField>` | `core::button.open-modal-field` |
| `<x-button.openModalValue>` | `core::button.open-modal-value` |
| `<x-button.popup>` | `core::button.popup` |
| `<x-button.props>` | `core::button.props` |
| `<x-button.raw>` | `core::button.raw` |
| `<x-card>` | `core::card.index` |
| `<x-counter>` | `core::counter` |
| `<x-desc>` | `core::field.desc.index` |
| `<x-desc.group>` | `core::field.desc.group` |
| `<x-desc.list>` | `core::field.desc.item` |
| `<x-dropdown>` | `core::dropdown.index` |
| `<x-dropdown.click>` | `core::dropdown.click` |
| `<x-dropdown.link>` | `core::dropdown.link` |
| `<x-dropdown.list>` | `core::dropdown.list` |
| `<x-dropdown.sort>` | `core::dropdown.sort` |
| `<x-field>` | `core::field.html.default.input` |
| `<x-field.group>` | `core::field.html.default.group` |
| `<x-field.input>` | `core::field.html.default.input` |
| `<x-field.select>` | `core::field.html.default.select` |
| `<x-field.text>` | `core::field.html.default.text` |
| `<x-field.textarea>` | `core::field.html.default.text` |
| `<x-form>` | `core::form.index` |
| `<x-form.cancel>` | `core::form.cancel` |
| `<x-form.group>` | `core::form.group` |
| `<x-form.input>` | `core::form.input` |
| `<x-form.label>` | `core::form.label` |
| `<x-form.select>` | `core::form.select` |
| `<x-form.submit>` | `core::form.submit` |
| `<x-form.text>` | `core::form.textarea` |
| `<x-form.textarea>` | `core::form.textarea` |
| `<x-icon>` | `core::icon` |
| `<x-input>` | `core::field.html.default.input` |
| `<x-link>` | `core::button.index` |
| `<x-modal>` | `core::modal.index` |
| `<x-modal.button>` | `core::modal.button` |
| `<x-modal.msg>` | `core::modal.msg` |
| `<x-modal.save>` | `core::modal.save` |
| `<x-nav.item>` | `core::nav.item` |
| `<x-nav.link>` | `core::nav.link` |
| `<x-nav.pagination-buttons>` | `core::nav.pagination-buttons` |
| `<x-nav.tabs>` | `core::nav.tabs` |
| `<x-show.info>` | `core::show.info` |
| `<x-show.item>` | `core::show.item` |
| `<x-slim>` | `core::field.wire.default.input` |
| `<x-slim.input>` | `core::field.wire.default.input` |
| `<x-slim.select>` | `core::field.wire.default.select` |
| `<x-slim.text>` | `core::field.wire.default.text` |
| `<x-submit>` | `core::button.submit` |
| `<x-table>` | `core::table.index` |
| `<x-table.cell>` | `core::table.cell` |
| `<x-table.check>` | `core::table.check` |
| `<x-table.filter>` | `core::table.filter.index` |
| `<x-table.filter.cnt_per_page>` | `core::table.filter.dropdown-cnt_per_page` |
| `<x-table.filter.dropdown>` | `core::table.filter.dropdown` |
| `<x-table.filter.dropdown.bool>` | `core::table.filter.dropdown-bool` |
| `<x-table.filter.dropdown.enum>` | `core::table.filter.dropdown-enum` |
| `<x-table.filter.search>` | `core::table.filter.search` |
| `<x-table.filter.select>` | `core::table.filter.select` |
| `<x-table.head.sort>` | `core::table.head.sort` |
| `<x-table.head.toggle>` | `core::table.head.toggle` |
| `<x-table.row>` | `core::table.row.index` |
| `<x-table.row.line1>` | `core::table.row.line1` |
| `<x-table.row.line2>` | `core::table.row.line2` |
| `<x-table.toggle>` | `core::table.toggle` |
| `<x-wire>` | `core::field.wire.float.input` |
| `<x-wire.input>` | `core::field.wire.float.input` |
| `<x-wire.search>` | `core::field.wire.float.search` |
| `<x-wire.select>` | `core::field.wire.float.select` |
| `<x-wire.select-enum>` | `core::field.wire.float.select-enum` |
| `<x-wire.text>` | `core::field.wire.float.text` |
