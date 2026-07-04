<p align="center"><img src="knospe-logo.svg" alt="Knospe" width="88" height="88" /></p>

# Knospe - Handbuch

Willkommen. Dieses Handbuch begleitet dich Schritt für Schritt: vom ersten Start über die Grundlagen von PHP und der Projektarchitektur bis zu Datenbank, Frontend, eigenen Plugins, Betrieb, Werkzeugen und Sicherheit. Unten ist jedes Dokument einzeln verlinkt.

## Lernpfade

- Anfänger (wenig oder keine Vorkenntnisse): [00-start](00-start/00-einstieg-fuer-anfaenger.md) -> [01-grundlagen](01-grundlagen/01-oop-klassen-intro.md) -> [02-architektur](02-architektur/01-psr-standards-intro.md) -> [06-deployment](06-deployment/01-docker-compose-detailliert.md) -> [04-frontend](04-frontend/01-svelte5-komponenten-intro.md)
- Fortgeschrittene (PHP-Erfahrung): [02-architektur](02-architektur/01-psr-standards-intro.md) -> [03-datenbank](03-datenbank/01-postgresql-setup-docker.md) -> [05-plugins](05-plugins/01-plugin-system-konzept.md)
- Betrieb: [06-deployment](06-deployment/01-docker-compose-detailliert.md) -> [07-werkzeuge](07-werkzeuge/01-phpstorm-installation.md) -> [08-debugging-und-sicherheit](08-debugging-und-sicherheit/01-fehlertypen-verstehen.md)
- Frontend-Fokus: [04-frontend](04-frontend/01-svelte5-komponenten-intro.md) -> [02-architektur](02-architektur/03-request-flow-diagramm.md) (Ablauf einer Anfrage)

## Alle Dokumente

### 00 - Einstieg und Installation

- [Einstieg für Anfänger](00-start/00-einstieg-fuer-anfaenger.md)
- [Was ist Knospe?](00-start/01-was-ist-knospe.md)
- [Schnellstart mit Docker](00-start/02-installation-docker.md)
- [Installation ohne Docker](00-start/03-installation-bare-metal.md)
- [Datei-Übersicht](00-start/04-datei-uebersicht.md)
- [Projektstruktur verstehen](00-start/05-projektstruktur-bedeutung.md)

### 01 - PHP-Grundlagen

- [Klassen und Objekte](01-grundlagen/01-oop-klassen-intro.md)
- [Vererbung](01-grundlagen/02-oop-vererbung.md)
- [Interfaces und Traits](01-grundlagen/03-oop-interfaces-und-traits.md)
- [Constructor Promotion und readonly](01-grundlagen/04-properties-constructor-promotion.md)
- [Property Hooks (PHP 8.4)](01-grundlagen/05-property-hooks.md)
- [Wann Enums?](01-grundlagen/06-enums-konzept.md)
- [Enums in der Praxis](01-grundlagen/07-enums-praxis.md)
- [Wann Attribute?](01-grundlagen/08-attributes-konzept.md)
- [Attribute in der Praxis](01-grundlagen/09-attributes-praxis.md)
- [First-Class-Callables](01-grundlagen/10-first-class-callables.md)
- [Der Pipe-Operator](01-grundlagen/11-pipe-operator.md)
- [Glossar: Grundlagen](01-grundlagen/glossar-grundlagen.md)

### 02 - Architektur

- [PSR-Standards](02-architektur/01-psr-standards-intro.md)
- [Ordner im Detail](02-architektur/02-ordner-bedeutung-detailliert.md)
- [Der Weg einer Anfrage](02-architektur/03-request-flow-diagramm.md)
- [Dependency Injection](02-architektur/04-dependency-injection.md)
- [Das Service-Muster](02-architektur/05-services-pattern.md)
- [Middleware-Pipeline](02-architektur/06-middleware-pipeline.md)
- [Zentrale Fehlerbehandlung](02-architektur/07-exception-handling-zentral.md)
- [HTTP-Methoden](02-architektur/08-http-method-semantik.md)
- [REST kurz erklärt](02-architektur/09-restful-apis-kurz.md)
- [Konfiguration und Umgebung](02-architektur/10-konfiguration-und-umgebung.md)
- [Glossar: Architektur](02-architektur/glossar-architektur.md)

### 03 - Datenbank

- [PostgreSQL mit Docker](03-datenbank/01-postgresql-setup-docker.md)
- [Zugangsdaten verwalten](03-datenbank/02-zugangsdaten-verwalten.md)
- [PDO und sichere Abfragen](03-datenbank/03-pdo-und-sql.md)
- [Migrationen](03-datenbank/04-migrationen-system.md)
- [Erste Tabelle anlegen](03-datenbank/05-erste-tabelle-anlegen.md)
- [SQL-Grundlagen](03-datenbank/06-sql-basics.md)
- [Beziehungen und Fremdschlüssel](03-datenbank/07-beziehungen-foreign-keys.md)
- [DataGrip einrichten](03-datenbank/08-datagrip-einrichtung.md)
- [Das Repository-Muster](03-datenbank/09-repository-pattern.md)
- [Glossar: Datenbank](03-datenbank/glossar-datenbank.md)

### 04 - Frontend

- [Svelte-5-Komponenten](04-frontend/01-svelte5-komponenten-intro.md)
- [TypeScript in Svelte](04-frontend/02-typescript-in-svelte.md)
- [API-Aufrufe mit fetch](04-frontend/03-api-aufrufe-fetch.md)
- [Zustand teilen](04-frontend/04-state-management-einfach.md)
- [Vite und Werkzeuge](04-frontend/05-vite-und-tooling.md)
- [Styling](04-frontend/06-styling-css.md)
- [Routing in der SPA](04-frontend/07-routing-sveltekit.md)
- [Formulare und Validierung](04-frontend/08-forms-und-validierung.md)
- [Glossar: Frontend](04-frontend/glossar-frontend.md)

### 05 - Plugins

- [Das Plugin-System](05-plugins/01-plugin-system-konzept.md)
- [Aufbau eines Plugins](05-plugins/02-plugin-struktur.md)
- [Hooks: Aktionen und Filter](05-plugins/03-hooks-und-events.md)
- [Dein erstes Plugin](05-plugins/04-dein-erstes-plugin.md)
- [Plugin mit eigener Tabelle](05-plugins/05-plugin-datenzugriff.md)
- [Plugin-Einstellungen](05-plugins/06-plugin-konfiguration.md)
- [Plugins testen](05-plugins/07-plugin-testing.md)
- [Plugin weitergeben](05-plugins/08-plugin-veroeffentlichen.md)

### 06 - Betrieb und Deployment

- [Docker Compose im Detail](06-deployment/01-docker-compose-detailliert.md)
- [Docker im Alltag](06-deployment/02-docker-befehle-alltag.md)
- [Logs und Fehlersuche](06-deployment/03-logs-und-debugging.md)
- [Betrieb ohne Docker](06-deployment/04-bare-metal-installation.md)
- [Produktions-Checkliste](06-deployment/05-produktions-checkliste.md)
- [Umgebungen verwalten](06-deployment/06-environment-verwaltung.md)
- [Skalierung und Leistung](06-deployment/07-skalierung-und-performance.md)
- [Glossar: Betrieb](06-deployment/glossar-betrieb.md)

### 07 - Werkzeuge

- [PhpStorm einrichten](07-werkzeuge/01-phpstorm-installation.md)
- [PHP-Interpreter über Docker](07-werkzeuge/02-phpstorm-docker-interpreter.md)
- [PHPUnit in PhpStorm](07-werkzeuge/03-phpunit-in-phpstorm.md)
- [Debuggen mit Xdebug](07-werkzeuge/04-xdebug-debugging.md)
- [DataGrip verbinden](07-werkzeuge/05-datagrip-verbindung.md)
- [SQL in DataGrip](07-werkzeuge/06-sql-queries-in-datagrip.md)
- [Code-Inspektion](07-werkzeuge/07-phpstorm-code-inspection.md)
- [PHP-CS-Fixer nutzen](07-werkzeuge/08-php-cs-fixer-in-phpstorm.md)
- [Git in PhpStorm](07-werkzeuge/09-git-integration.md)
- [Glossar: Werkzeuge](07-werkzeuge/glossar-werkzeuge.md)

### 08 - Debugging und Sicherheit

- [Fehlertypen verstehen](08-debugging-und-sicherheit/01-fehlertypen-verstehen.md)
- [Fehlerprotokolle lesen](08-debugging-und-sicherheit/02-error-log-lesen.md)
- [var_dump vs. Debugger](08-debugging-und-sicherheit/03-var-dump-vs-debug.md)
- [Exceptions und Fehler](08-debugging-und-sicherheit/04-exceptions-vs-errors.md)
- [Breakpoints und Schrittausführung](08-debugging-und-sicherheit/05-breakpoints-und-stepping.md)
- [Debugging-Strategien](08-debugging-und-sicherheit/06-debugging-strategien.md)
- [Sicherheits-Grundlagen](08-debugging-und-sicherheit/07-sicherheits-basics.md)
- [Passwörter sicher speichern](08-debugging-und-sicherheit/08-passwort-sicherheit.md)
- [HTTPS und TLS](08-debugging-und-sicherheit/09-https-und-ssl.md)
- [CORS verstehen](08-debugging-und-sicherheit/10-cors-verstehen.md)
- [Rate-Limiting](08-debugging-und-sicherheit/11-rate-limiting.md)
- [Logging richtig](08-debugging-und-sicherheit/12-logging-best-practices.md)
- [Glossar: Sicherheit](08-debugging-und-sicherheit/glossar-sicherheit.md)

### 09 - Entwicklung und Beitragen

- [Codestil (PSR-12)](09-entwicklung/01-code-style-psr-12.md)
- [Namensregeln](09-entwicklung/02-naming-konventionen.md)
- [Docblocks und Kommentare](09-entwicklung/03-docblocks-und-comments.md)
- [Testkultur](09-entwicklung/04-testing-kultur.md)
- [Beitragen](09-entwicklung/05-beitragen.md)
- [Glossar: Entwicklung](09-entwicklung/glossar-entwicklung.md)

## Nachschlagen

- [Knospe-Glossar](glossar.md) - zentrale Begriffe
- [Quellen und Links](referenzen/quellen-und-links.md) - kuratierte, vorrangig deutschsprachige Quellen
- Spickzettel:
  - [Spickzettel: Terminal](referenzen/spickzettel/cli.md)
  - [Spickzettel: Composer](referenzen/spickzettel/composer.md)
  - [Spickzettel: Docker](referenzen/spickzettel/docker.md)
  - [Spickzettel: Git](referenzen/spickzettel/git.md)
  - [Spickzettel: PHP](referenzen/spickzettel/php.md)
  - [Spickzettel: SQL/PostgreSQL](referenzen/spickzettel/sql-postgresql.md)
  - [Spickzettel: Svelte 5 + TypeScript](referenzen/spickzettel/svelte5-typescript.md)

## Kopplung Code und Handbuch

Im Boilerplate-Code stehen Hinweise wie `// Lern mehr: ./docs/02-architektur/04-dependency-injection.md`. Sie zeigen genau dort, wo es vertiefend weitergeht. Umgekehrt verlinken die Anleitungen auf die echten Code-Dateien.
