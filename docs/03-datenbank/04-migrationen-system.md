# Migrationen

Eine Datenbank hat ein Schema: welche Tabellen es gibt, welche Spalten. Dieses Schema ändert sich mit der Zeit. Migrationen sind kleine, nummerierte Schritte, die das Schema aufbauen - versioniert und wiederholbar. Das WARUM: Jeder im Team kommt so zum exakt gleichen Stand, ohne Klick-Anleitungen.

## Nummerierte SQL-Dateien

Die Migrationen liegen in `backend/database/migrations` und heißen `001_...`, `002_..., 003_...`. Die Nummer bestimmt die Reihenfolge. Jede Datei ist reines SQL, mit einem UP-Teil (was passieren soll) und optional einem DOWN-Teil (wie man es rückgängig macht), getrennt durch die Zeile `-- DOWN:`:

```sql
CREATE TABLE IF NOT EXISTS users (
    id           BIGSERIAL PRIMARY KEY,
    email        VARCHAR(255) NOT NULL UNIQUE,
    display_name VARCHAR(120) NOT NULL
);

-- DOWN:
DROP TABLE IF EXISTS users CASCADE;
```

## Was schon lief: schema_migrations

Der `MigrationRunner` (`backend/src/Database/Migration/MigrationRunner.php`) merkt sich in einer eigenen Tabelle `schema_migrations`, welche Versionen schon ausgeführt wurden. Beim nächsten Lauf werden nur die neuen ausgeführt - vorhandene nicht doppelt.

## Transaktion je Migration

PostgreSQL kann Schema-Änderungen zurücknehmen. Darum läuft jede Migration in einer eigenen Transaktion: Klappt der UP-Teil, wird der Eintrag in `schema_migrations` geschrieben und alles bestätigt (`commit`). Bricht etwas ab, wird alles zurückgerollt (`rollBack`) - keine halben Zustände. Mehr dazu unter [PDO und sichere Abfragen](03-pdo-und-sql.md).

## Ausführen

Am bequemsten über das Verwaltungsskript:

```
./knospe migrate
```

Dahinter steckt die CLI `backend/bin/console`, die du auch direkt im Container aufrufen kannst:

```
php bin/console migrate       Ausstehende Migrationen ausführen
php bin/console rollback [n]  Die letzten n Migrationen zurücknehmen
php bin/console status        Zeigen, was ausgeführt ist
php bin/console seed          Startdaten einspielen
```

Wie du selbst eine Migration schreibst, zeigt [Erste Tabelle anlegen](05-erste-tabelle-anlegen.md).
