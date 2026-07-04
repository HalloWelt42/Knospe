# Knospe - Handbuch

Willkommen. Dieses Handbuch begleitet dich Schritt fuer Schritt: vom ersten Start ueber die Grundlagen von PHP und der Projektarchitektur bis zu Datenbank, Frontend, eigenen Plugins, Betrieb, Werkzeugen und Sicherheit.

> Hinweis: Das Projekt wird in Meilensteinen aufgebaut. Die Leitfaeden werden begleitend gefuellt. Vorhandene Kapitel sind unten verlinkt, geplante sind als solche erkennbar.

## Lernpfade

- Anfaenger (wenig oder keine Vorkenntnisse): `00-start` -> `01-grundlagen` -> `02-architektur` -> `06-deployment` -> `04-frontend`
- Fortgeschrittene (PHP-Erfahrung): `02-architektur` -> `03-datenbank` -> `05-plugins`
- Betrieb: `06-deployment` -> `07-werkzeuge` -> `08-debugging-und-sicherheit`
- Frontend-Fokus: `04-frontend` -> `02-architektur` (Ablauf einer Anfrage)

## Themenbloecke

- `00-start/` - Einstieg, Schnellstart (Docker und lokal), Datei-Uebersicht, Projektstruktur
- `01-grundlagen/` - OOP in PHP, wann Enums, wann Attribute, moderne Syntax
- `02-architektur/` - Kernaufbau, Anfrageablauf, Dependency Injection, Middleware, Fehlerbehandlung
- `03-datenbank/` - PostgreSQL, Zugangsdaten, PDO/SQL, Migrationen, Repository-Muster
- `04-frontend/` - Svelte 5 (Runes), TypeScript, API-Aufrufe, Zustand, Vite
- `05-plugins/` - Plugin-System, Hooks/Filter, dein erstes Plugin
- `06-deployment/` - Docker im Detail, Alltag, Bare-Metal, Produktion
- `07-werkzeuge/` - PhpStorm, DataGrip, Xdebug, Tests im Werkzeug
- `08-debugging-und-sicherheit/` - Fehlersuche, Sicherheit, Passwoerter, CORS
- `09-entwicklung/` - Codestil, Namensregeln, Testkultur, Beitragen

## Nachschlagen

- `referenzen/quellen-und-links.md` - kuratierte, vorrangig deutschsprachige Quellen
- `referenzen/spickzettel/` - kompakte Uebersichtsblaetter je Sprache und Werkzeug
- `glossar.md` - zentrale Begriffe

## Kopplung Code und Handbuch

Im Boilerplate-Code stehen Hinweise wie `// Lern mehr: docs/02-architektur/04-dependency-injection.md`. Sie zeigen genau dort, wo es vertiefend weitergeht.
