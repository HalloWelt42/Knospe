# Knospe-Glossar

Diese Sammlung erklärt die wichtigsten Begriffe, die dir in Knospe immer wieder begegnen. Jeder Eintrag hat einen Satz und verweist auf die Stelle im Code oder auf die passende Doku. So kannst du beim Lesen schnell nachschlagen, ohne den Faden zu verlieren.

## Backend-Begriffe (PHP)

- **Klasse**: Bauplan für Objekte mit Daten und Verhalten, etwa `src/Domain/Post/Post.php`.
- **Interface**: Vertrag, der Methoden vorschreibt, aber nicht ausprogrammiert, zum Beispiel `PostRepositoryInterface`.
- **Enum**: Aufzählungstyp mit festen erlaubten Werten, in PHP als `enum` deklariert.
- **Attribut**: Metadaten direkt am Code in eckigen Klammern, etwa `#[Route]`, vom Kern ausgewertet.
- **Middleware**: Schicht in der Pipeline, die eine Anfrage vor oder nach dem Handler bearbeitet, siehe `src/Http/Middleware/`.
- **Repository**: Kapselt den Datenbankzugriff einer Domäne, zum Beispiel `PostgresPostRepository`.
- **Service**: Bündelt Geschäftslogik über Repositories, etwa `PostService`.
- **Hook**: Erweiterungspunkt via `doAction`/`applyFilter`, verwaltet vom `HookDispatcher`.
- **Plugin**: Optionales Zusatzmodul mit `manifest.json`, siehe `plugins/knospe-comments`.
- **DI (Dependency Injection)**: Abhängigkeiten werden von aussen hineingereicht, hier vom `Container` mit Autowiring.
- **PSR-4**: Standard, der Namensraum `Knospe\` auf `backend/src` abbildet.
- **REST**: Stil für HTTP-APIs mit klaren Ressourcen und Methoden, siehe [Endpoint-Übersicht](02-architektur/09-restful-apis-kurz.md).
- **Endpoint**: Konkrete Adresse plus Methode, etwa `GET /api/posts`.
- **PDO**: PHP-Datenbankschicht, in `src/Database/Connection.php` mit dem pgsql-Treiber.
- **Migration**: Versionierte Schemaänderung in `database/migrations`, ausgeführt vom `MigrationRunner`.

## Frontend-Begriffe (Svelte 5)

- **Komponente**: Wiederverwendbarer UI-Baustein in `frontend/src/components/`, etwa `PostItem.svelte`.
- **Rune**: Reaktives Sprachmittel in Svelte 5, zum Beispiel `$state`, `$derived`, `$effect`.
- **Prop**: Von aussen übergebener Wert einer Komponente, deklariert mit `$props()`.
- **Slot**: Platzhalter für eingesetzten Inhalt, hier für Plugins in `PluginSlot.svelte`.

## Werkzeuge

- **Xdebug**: Debugger für PHP, läuft über Port 9003, siehe [Debugging](07-werkzeuge/04-xdebug-debugging.md).
- **Breakpoint**: Haltepunkt im Code, an dem der Debugger anhält, um Werte zu prüfen.
