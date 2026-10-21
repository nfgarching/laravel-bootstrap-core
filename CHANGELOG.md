# Change Log

All notable changes to this package will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased] - 2025-12-06

### Added

- 2026-06-12: Blade-Komponenten per `Blade::anonymousComponentPath()` als
  `<x-core::...>` / `<x-core::...>` verfügbar.
- 2026-06-12: `x-tags`-Header – jede Komponente deklariert ihre Tag-Aliase
  selbst in der ersten Zeile ihrer Blade-Datei; Registrierung über
  `LibraryServiceProvider::registerComponentTags()`.
  Siehe `docs/komponenten-registrierung.md`.

### Changed

- 2026-10-21: Package von `nfgarching/components` in `nfgarching/core`
  umbenannt (PHP-Namespace `Nfgarching\Core`). Die beiden Komponenten-
  Verzeichnisse `resources/views/components/` und `src/Components/` sind
  unter einem View-Namespace `core::` zusammengeführt (vorher `component::`
  bzw. `components::`). Livewire-Partials: `core-livewire::`. Routen-Präfix
  `core.` statt `components.`. Toter Code (`_deprecated/`, `.template/`,
  `layouts_del/`, `*.bak`, Kopien, `AlertComponent`) nicht übernommen.
- 2026-06-12: Zentrale `Blade::component()`-Liste in `bootComponents()`
  entfernt (ersetzt durch `x-tags`-Header, Verhalten unverändert).
- 2026-06-12: Routen von `src/Web.php` nach `routes/web.php` und
  `routes/Settings.php` verschoben.

### Fixed

### KI - Commands

#### ImportAkVAktem

