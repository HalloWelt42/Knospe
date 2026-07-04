# Knospe - Handbuch

Willkommen. Dieses Handbuch begleitet dich Schritt für Schritt: vom ersten Start über die Grundlagen von PHP und der Projektarchitektur bis zu Datenbank, Frontend, eigenen Plugins, Betrieb, Werkzeugen und Sicherheit.

## Lernpfade

- Anfänger (wenig oder keine Vorkenntnisse): [00-start](00-start/00-einstieg-fuer-anfaenger.md) -> [01-grundlagen](01-grundlagen/01-oop-klassen-intro.md) -> [02-architektur](02-architektur/01-psr-standards-intro.md) -> [06-deployment](06-deployment/01-docker-compose-detailliert.md) -> [04-frontend](04-frontend/01-svelte5-komponenten-intro.md)
- Fortgeschrittene (PHP-Erfahrung): [02-architektur](02-architektur/01-psr-standards-intro.md) -> [03-datenbank](03-datenbank/01-postgresql-setup-docker.md) -> [05-plugins](05-plugins/01-plugin-system-konzept.md)
- Betrieb: [06-deployment](06-deployment/01-docker-compose-detailliert.md) -> [07-werkzeuge](07-werkzeuge/01-phpstorm-installation.md) -> [08-debugging-und-sicherheit](08-debugging-und-sicherheit/01-fehlertypen-verstehen.md)
- Frontend-Fokus: [04-frontend](04-frontend/01-svelte5-komponenten-intro.md) -> [02-architektur](02-architektur/03-request-flow-diagramm.md) (Ablauf einer Anfrage)

## Themenblöcke

- [00-start](00-start/) - Einstieg, Schnellstart (Docker und lokal), Datei-Übersicht, Projektstruktur
- [01-grundlagen](01-grundlagen/) - OOP in PHP, wann Enums, wann Attribute, moderne Syntax
- [02-architektur](02-architektur/) - Kernaufbau, Anfrageablauf, Dependency Injection, Middleware, Fehlerbehandlung
- [03-datenbank](03-datenbank/) - PostgreSQL, Zugangsdaten, PDO/SQL, Migrationen, Repository-Muster
- [04-frontend](04-frontend/) - Svelte 5 (Runes), TypeScript, API-Aufrufe, Zustand, Vite
- [05-plugins](05-plugins/) - Plugin-System, Hooks/Filter, dein erstes Plugin
- [06-deployment](06-deployment/) - Docker im Detail, Alltag, Bare-Metal, Produktion
- [07-werkzeuge](07-werkzeuge/) - PhpStorm, DataGrip, Xdebug, Tests im Werkzeug
- [08-debugging-und-sicherheit](08-debugging-und-sicherheit/) - Fehlersuche, Sicherheit, Passwörter, CORS
- [09-entwicklung](09-entwicklung/) - Codestil, Namensregeln, Testkultur, Beitragen

## Nachschlagen

- [referenzen/quellen-und-links.md](referenzen/quellen-und-links.md) - kuratierte, vorrangig deutschsprachige Quellen
- [referenzen/spickzettel](referenzen/spickzettel/) - kompakte Übersichtsblätter je Sprache und Werkzeug
- [glossar.md](glossar.md) - zentrale Begriffe

## Kopplung Code und Handbuch

Im Boilerplate-Code stehen Hinweise wie `// Lern mehr: ./docs/02-architektur/04-dependency-injection.md`. Sie zeigen genau dort, wo es vertiefend weitergeht.
