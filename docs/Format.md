# Formatierungsvorgaben – Bootstrap-5-Thema

Verbindliche Vorgaben für die Gestaltung von Blade-/Livewire-Views in diesem Projekt.
Ziel: einheitliches Erscheinungsbild im **Bootstrap-5-Thema**, das die App durchgängig verwendet.

## Grundlagen

- Das Layout `<x-main-layout>` lädt **Bootstrap 5 CSS + Bootstrap Icons per CDN**
  (siehe `packages/nfgarching/core/resources/views/layouts/parts/head.blade.php`).
  → Bootstrap-Utility- und Komponentenklassen funktionieren ohne `npm run build`.
- **Keine Tailwind-Klassen** in neuen/angepassten Views verwenden (z. B. kein `rounded-lg`,
  `bg-blue-600`, `grid grid-cols-2`, `text-right`, `divide-y`).
- Die gemeinsame Komponentenbibliothek `Nfgarching\Core` bevorzugen. Komponenten werden
  **ohne Namespace-Präfix** eingebunden (`<x-card>`, `<x-button>`, `<x-table>` …).
- Domänensprache ist **Deutsch**; Texte über `{{ __('…') }}` ausgeben.

## Komponentenbibliothek (bevorzugt nutzen)

| Komponente | Zweck | Wichtige Props |
|---|---|---|
| `<x-card>` | Container mit Header/Body/Footer | Slots: `headerLeft`, `headerRight`, `header`, `footer` |
| `<x-button>` | Button oder Link (themetreu) | `type` (Bootstrap-Variante), `click` (→ `wire:click`), `href`, `tooltip`, `label` |
| `<x-button.group>` | Mehrere Buttons gruppieren | – |
| `<x-table>`, `<x-table.row.line1/line2>`, `<x-table.head.sort>` | Tabellen mit Sortier-/Accordion-Mustern | `acc_id`, `row_id`, `toggleChecks` |
| `<x-modal>`, `<x-modal.save>` | Bootstrap-Modals (Livewire-fähig) | `modid`, `title`, `btnSaving`, `btnCancel` |

- `<x-button type="primary" click="save">` rendert `<button class="btn btn-primary" type="button">`
  und ergänzt bei `click` automatisch `wire:loading.attr="disabled"`.
- Mit `href` wird stattdessen ein `<a class="btn …" role="button">` gerendert.
- `type` akzeptiert alle Bootstrap-Varianten: `primary`, `secondary`, `success`, `danger`,
  `warning`, `info`, `light`, `dark` sowie `outline-*`.

> **Hinweis:** Die Feldkomponenten `<x-form.*>` / `<x-wire.*>` sind derzeit in Entwicklung und
> rendern Debug-Text in den Labels (`{{ $label }} - {{ $field }}`) bzw. erzwingen ein
> `form.`/`item.`-Präfix. Bis zur Stabilisierung für Livewire-Felder mit Top-Level-Properties
> oder Array-Bindings **direktes Bootstrap-Markup** mit beibehaltenen `wire:`-Direktiven verwenden.

## Formularfelder

**Floating Labels** sind der Standard für Kopf-/Detailformulare:

```blade
<div class="form-floating">
    <input type="text" id="title" placeholder="{{ __('Titel') }}"
           wire:model.live.debounce.500ms="title" class="form-control">
    <label for="title">{{ __('Titel') }}</label>
</div>
```

- Select: `<select class="form-select">` (im `form-floating` mit nachgestelltem `<label>`).
- Radio/Checkbox: `form-check` (+ `form-check-inline` für nebeneinander):
  ```blade
  <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" id="typA" wire:model.live="type" value="a">
      <label class="form-check-label" for="typA">{{ __('Variante A') }}</label>
  </div>
  ```
- Kompakte Inline-Felder (z. B. in Tabellen): `form-control-sm` / `form-select-sm`,
  Breiten über `style="max-width: …rem"`.
- `placeholder` bei `form-floating` setzen (von Bootstrap für die Label-Animation benötigt).

## Layout & Grid

| Tailwind | Bootstrap 5 |
|---|---|
| `grid grid-cols-2 gap-4` | `row g-3` + `col-md-6` / `col-6` |
| `flex gap-2` | `d-flex gap-2` |
| `flex flex-wrap gap-2` | `d-flex flex-wrap gap-2` |
| `mb-6` / `mb-4` | `mb-4` / `mb-3` |
| `text-right` | `text-end` |
| `text-center` | `text-center` |
| `tabular-nums` | `font-monospace` |
| `text-xs text-gray-400` | `small text-body-secondary` |
| `text-green-700` | `text-success` |
| `text-red-600` | `text-danger` |

## Tabellen

```blade
<div class="table-responsive mb-4">
    <table class="table table-sm table-hover table-striped align-middle">
        <thead class="table-light">
            <tr class="text-uppercase small text-body-secondary">
                <th>…</th>
                <th class="text-end">…</th>
            </tr>
        </thead>
        <tbody>
            <tr wire:key="row-{{ $index }}"> … </tr>
        </tbody>
    </table>
</div>
```

## Karten / Panels

- Hauptcontainer einer Seite: `<x-card>` mit `headerLeft`/`headerRight`-Slots.
- Hervorgehobene Infoblöcke innerhalb: `<div class="card bg-body-tertiary mb-4"><div class="card-body">…`.
- Trenner/Summenzeile: `border-top pt-2 mt-2 fw-semibold fs-5`.

## Icons

- **Bootstrap Icons** verwenden: `<i class="bi bi-x-lg"></i>`, `<i class="bi bi-pencil-square"></i>`.

## Livewire / Alpine

- Alle `wire:`-Direktiven beim Umbau **unverändert** beibehalten
  (`wire:model.live.debounce.500ms`, `wire:click`, `wire:loading`, `wire:key`, `wire:target`).
- Eine Livewire-Komponente braucht **genau ein** umschließendes Wurzelelement
  (`<div>`); `<x-card>` o. Ä. **innerhalb** dieser Wurzel platzieren.
- Lade-/Statusanzeigen: `wire:loading` / `wire:loading.remove` (+ `wire:target`).
- Modals mit `wire:ignore.self`, damit Bootstraps Modal-JS nicht mit Livewire kollidiert.

## Checkliste beim Umbau Tailwind → Bootstrap

1. Wurzel-`<div>` der Livewire-Komponente erhalten, Inhalt in `<x-card>` kapseln.
2. Alle Tailwind-Klassen gemäß Mapping-Tabellen ersetzen.
3. Buttons auf `<x-button>` umstellen, `wire:click` → `click`.
4. Formularfelder als `form-floating` / `form-check` aufbauen, `wire:`-Bindings beibehalten.
5. Icons auf `bi bi-*` umstellen.
6. Im Browser prüfen (kein `npm run build` nötig, da Bootstrap per CDN geladen wird).
