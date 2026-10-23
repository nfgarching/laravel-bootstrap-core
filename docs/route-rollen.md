# Rollen für Package-Routen (RouteRoles)

Das `core`-Package schützt seine eigenen Routen (Dashboard, Admin, Startseite,
Einstellungen, Props, Testseiten) zusätzlich zu `auth` mit der `role:`-Middleware
von [spatie/laravel-permission](https://spatie.be/docs/laravel-permission) –
**aber nur, wenn die Anwendung spatie verwendet**. Ohne spatie verhalten sich
die Routen wie bisher (`web` + `auth`), das Package hat keine Abhängigkeit
auf spatie.

Die Rollennamen kennt nur die Anwendung. Das Package arbeitet mit **Gruppen**
(`admin`, `partner`, `user`), denen die Anwendung per Config ihre Rollen
zuordnet.

## Bausteine

| Baustein | Aufgabe |
| --- | --- |
| `Nfgarching\Core\Helpers\RouteRoles` | Liefert die Middleware für eine Gruppe. |
| `config('app.route_roles')` | Rollen je Gruppe (Vorgabe im Package, Überschreibung in der Anwendung). |
| `LibraryServiceProvider::boot()` | Registriert den Alias `role`, falls die Anwendung das nicht selbst tut. |
| `routes/web.php`, `routes/settings.php` | Hängen die Middleware per `...RouteRoles::middleware('…')` an. |

### RouteRoles

```php
RouteRoles::middleware('admin');  // ['role:Administrator'] oder []
RouteRoles::enabled();            // spatie installiert und User-Model kennt Rollen?
```

`middleware()` gibt ein **leeres Array** zurück, wenn

- spatie nicht installiert ist (`Spatie\Permission\Middleware\RoleMiddleware`
  fehlt),
- das User-Model (`auth.providers.users.model`) keine Methode `hasAnyRole()`
  hat, also nicht den Trait `HasRoles` verwendet, oder
- für die Gruppe keine Rollen konfiguriert sind (`null`, `''`, `[]`).

Sonst entsteht `['role:Rolle1|Rolle2']`. Die Rollen dürfen in der Config als
String mit `|` oder als Array angegeben werden.

In den Routendateien wird das Ergebnis mit dem Spread-Operator eingefügt:

```php
Route::middleware(['web', 'auth', ...RouteRoles::middleware('admin')])->group(function () {
    // …
});
```

Mit spatie ergibt das `['web', 'auth', 'role:Administrator']`, ohne spatie
`['web', 'auth']`.

> **Warum der Alias-String `role:…` und nicht der Klassenname?**
> Die Anwendung prüft Links mit `@route('name') … @endroute`
> (`User::darfRoute()`). Diese Prüfung sucht in den Middlewares der Route nach
> Einträgen, die mit `role:` beginnen. Mit
> `RoleMiddleware::using(...)` (voller Klassenname) würden Links für Nutzer ohne
> passende Rolle nicht ausgeblendet.

### Alias `role`

Der Alias wird normalerweise von der Anwendung in `bootstrap/app.php`
registriert. Fehlt er, registriert ihn der `LibraryServiceProvider` vor dem
Laden der Routen selbst (`Router::aliasMiddleware('role', RoleMiddleware::class)`),
damit `role:…` auch in Anwendungen ohne diesen Eintrag funktioniert.

## Gruppen und Routen

| Gruppe | Routen | Vorgabe im Package |
| --- | --- | --- |
| `admin` | `admin`, `testform`, `testing`, `testpage`, `core.props.*` | `Administrator` |
| `partner` | `dashboard` | `Administrator\|Partner` |
| `user` | `startpage`, `raw`, `settings.edit`, `settings.update` | `null` (nur `auth`) |
| – | `logout` | immer ohne Rollenprüfung |
| – | `login`, `register`, `guestpage` | `guest` |
| – | `welcome`, `greeting_from_bootstrap` | ohne Login |

`logout` bleibt bewusst ohne Rolle, damit sich auch Nutzer ohne Rolle abmelden
können.

Nach dem Login leitet `AuthenticatedSessionController` per
`redirect()->intended(route('startpage'))` weiter: Wer vorher eine
geschützte Seite aufrufen wollte, landet dort, sonst auf der Startseite. Die
Startseite ist die Route mit der weitesten Rollengruppe. Das Dashboard ist
dafür ungeeignet, weil es auf `partner` beschränkt ist.

## Konfiguration

Vorgabe im Package (`config/app.php`, per `mergeConfigFrom()` unter `app`):

```php
'route_roles' => [
    'admin' => 'Administrator',
    'partner' => 'Administrator|Partner',
    'user' => null,
],
```

Die Anwendung überschreibt den Schlüssel in ihrer eigenen `config/app.php`.
**Wichtig:** `mergeConfigFrom()` mischt nur die oberste Ebene. Definiert die
Anwendung `route_roles`, ersetzt das die **komplette** Vorgabe. Fehlt dort eine
Gruppe, gilt für deren Routen keine Rollenprüfung. Deshalb immer alle Gruppen
angeben.

Beispiel aus AkV 2026 (Rollen aus `App\Enums\Rolle`):

```php
use App\Enums\Rolle;

'route_roles' => [
    'admin' => Rolle::Administrator->value,
    'partner' => [Rolle::Administrator->value, Rolle::Partner->value],
    'user' => array_column(Rolle::cases(), 'value'),
],
```

> Die `role:`-Middleware kennt keine Rangfolge. Der Administrator muss in jeder
> Gruppe ausdrücklich genannt werden, in der er Zugriff haben soll.

## Verhalten

| Situation | Ergebnis |
| --- | --- |
| Gast | Weiterleitung zum Login (`auth`) |
| Angemeldet, Rolle passt | Seite wird angezeigt |
| Angemeldet, Rolle passt nicht | 403 (spatie `UnauthorizedException`) |
| Angemeldet ohne Rolle, Gruppe hat Rollen | 403 |
| spatie nicht installiert | nur `auth`, wie vor der Rollenprüfung |

Links in Sidebar und Offcanvas der Anwendung werden mit `@route('name')`
eingeblendet und verschwinden damit automatisch für Nutzer ohne passende Rolle.
Die Rollen müssen nur in der Config gepflegt werden.

## Eine neue Gruppe anlegen

1. Gruppe mit Vorgabe in `config/app.php` des Packages unter `route_roles`
   eintragen.
2. In `routes/web.php` die Routen mit `...RouteRoles::middleware('neu')`
   versehen.
3. In der Anwendung die Gruppe in `config/app.php` ergänzen. Ohne diesen
   Schritt ist sie dort ungeschützt (siehe Hinweis zu `mergeConfigFrom()`).
4. `php artisan route:list -v` bzw. `--json` zeigt die wirksame Middleware je
   Route.

## Prüfen

```bash
php artisan route:list --name=dashboard -v
php artisan config:clear   # nach Änderungen an route_roles bei gecachter Config
```

Tests in AkV 2026: `tests/Feature/Auth/RollenZugriffTest.php` (Zugriff je
Rolle, `darfRoute()`, Weiterleitung nach dem Login).
