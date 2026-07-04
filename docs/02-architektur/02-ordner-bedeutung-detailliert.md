# Ordner im Detail

Der Backend-Code liegt unter `backend/src` und ist nach Verantwortung sortiert. Jeder Ordner hat genau eine Aufgabe. Wer das versteht, findet jede Datei blind. Der Namespace `Knospe\` bildet diese Ordner per PSR-4 ab (siehe [PSR-Standards](01-psr-standards-intro.md)).

**Schnellkarte:**

- **`src/Core/`** - das Herz ohne Fachwissen: `Container.php` (Dienste bauen), `Router.php` (Pfad zu Handler), `Pipeline.php` und `MiddlewareHandler.php` (Middleware-Kette), `Kernel.php` (alles verdrahten) und `Exception/` (die HTTP-Fehlerfamilie: `HttpException`, `NotFoundException`, `MethodNotAllowedException`, `UnauthorizedException`, `ForbiddenException`, `ValidationException`).
- **`src/Http/`** - alles, was HTTP betrifft: `Json.php` (Antworten bauen), `Input.php` (JSON-Rumpf lesen), `Middleware/` (`ErrorHandlingMiddleware`, `CsrfMiddleware`) und `Handler/` (je ein Handler pro Route, z.B. `Handler/Post/CreatePostHandler.php`).
- **`src/Domain/`** - die Fachlogik, gruppiert nach Thema: `Post/` und `User/`. Jedes Thema hat eine Entity (`Post.php`, readonly), ein `...RepositoryInterface`, eine Postgres-Umsetzung und einen `...Service` mit der Geschäftslogik.
- **`src/Support/`** - kleine Helfer ohne HTTP-Bezug: `Config.php` (aus Env), `Session.php`, `PasswordHasher.php` (Argon2id), `AuthContext.php` (wer ist angemeldet).
- **`src/Database/`** - `Connection.php` (PDO/pgsql) und `Migration/` (`MigrationRunner`, `SeedRunner`).
- **`src/Plugin/`** - das Erweiterungssystem: `PluginManager`, `PluginLoader`, `PluginService`, `RouteRegistry`, `Hook/HookDispatcher` und die Verträge.

Außerhalb von `src`:

- **`backend/config/`** - `config.php` (baut das Config-Objekt) und `routes.php` (die deklarative Routentabelle).
- **[`backend/public/index.php`](../../backend/public/index.php)** - der Front-Controller, der einzige Einstiegspunkt.
- **`database/migrations`** und **`database/seeds`** - Schemastand und Beispieldaten.
- **`tests/`** - `Unit/` (einzelne Klassen) und `Integration/` (mit echter Datenbank, jeweils in einer Transaktion mit Rollback).

Eine Faustregel: Kommt eine neue Fachfunktion, wandert sie nach `Domain/`. Kommt eine neue Route, brauchst du einen `Handler/` und einen Eintrag in `routes.php`. Der Weg dahin steht in [Der Weg einer Anfrage](03-request-flow-diagramm.md).
