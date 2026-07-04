# Datei-Übersicht

Damit du dich zurechtfindest, hier der grobe Baum des Projekts. Jeder Eintrag bekommt einen Satz. Details folgen in den jeweiligen Kapiteln.

```
Knospe/
  knospe                Verwaltungsskript, einziger Einstieg für den Alltag
  docker-compose.yml    beschreibt die Container (PHP, PostgreSQL, Node)
  .env / .env.example   Konfiguration mit eindeutigen Ports und Geheimnissen
  backend/              das PHP-Backend
  frontend/             die Svelte-5-Oberfläche
  plugins/              eigene Erweiterungen mit Beispiel-Plugin
  docs/                 dieses Handbuch
  docker/               Baurezepte für die Container
  tools/                Hilfsskripte für die Einrichtung
```

## backend/

- [`public/index.php`](../../backend/public/index.php) - der Front-Controller, hier kommt jede Anfrage an.
- `src/Core/` - der kleine Kern: Container, Router, Pipeline, Kernel, Ausnahmen.
- `src/Http/` - HTTP-Bausteine: JSON-Antworten, Eingaben, Middleware, Handler.
- `src/Domain/` - die Fachlogik zu `Post` und `User` (Entity, Repository, Service).
- `src/Support/` - Helfer: Konfiguration, Session, Passwort-Hashing, Auth-Kontext.
- `src/Database/` - Datenbankverbindung, Migrations- und Seed-Runner.
- `src/Plugin/` - das Plugin-System mit Hooks und Registry.
- `config/` - `config.php` und `routes.php`, die zentralen Einstellungen.
- `database/` - `migrations` (Schema-Schritte) und `seeds` (Startdaten).
- [`bin/console`](../../backend/bin/console) - das Kommandozeilenwerkzeug (`migrate`, `rollback`, `status`, `seed`).
- `tests/` - `Unit` und `Integration` für PHPUnit.

## frontend/

- [`src/main.ts`](../../frontend/src/main.ts) - der Startpunkt der Oberfläche.
- `src/lib/` - Bausteine: `api.ts` (API-Client), `auth.svelte.ts` (Anmeldezustand), `router.svelte.ts` (kleiner Hash-Router), `plugins.ts` (Plugin-Registry).
- `src/components/` - die Ansichten wie `PostsView.svelte`, `LoginView.svelte`, `PluginsView.svelte`.
- [`vite.config.ts`](../../frontend/vite.config.ts) - Vite-Konfiguration mit dem Proxy von `/api` zum Backend.

## plugins/

- `knospe-comments/` - ein vollständiges Beispiel-Plugin mit `manifest.json`, PHP-Code, Migrationen und Frontend.

## docker/, tools/

- [`docker/php/Dockerfile`](../../docker/php/Dockerfile) - Bauanleitung für den PHP-Container.
- [`tools/setup-env.sh`](../../tools/setup-env.sh) - erzeugt die [`.env`](../../.env) mit pfad-eindeutigen Ports.

Wie diese Teile zusammenspielen, erklärt [Projektstruktur verstehen](05-projektstruktur-bedeutung.md).
