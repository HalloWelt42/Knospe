# Erste Tabelle anlegen

Jetzt legst du selbst eine Tabelle an - als Migration, damit die Änderung versioniert und wiederholbar ist. Wir bauen als Beispiel eine Tabelle `tags` für Schlagwörter.

## Die Migration schreiben

Lege eine neue Datei in `backend/database/migrations` an. Die Nummer muss höher sein als alle vorhandenen, hier `004`:

`backend/database/migrations/004_create_tags_table.sql`

```sql
-- Migration 004: Tabelle "tags"
-- Speichert Schlagwörter. Der Name ist eindeutig.

CREATE TABLE IF NOT EXISTS tags (
    id         BIGSERIAL PRIMARY KEY,
    name       VARCHAR(60)  NOT NULL UNIQUE,
    created_at TIMESTAMPTZ  NOT NULL DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_tags_name ON tags (name);

-- DOWN:
DROP TABLE IF EXISTS tags CASCADE;
```

## Was die Zeilen bedeuten

- `BIGSERIAL PRIMARY KEY`: ein automatisch hochzählender Ganzzahl-Schlüssel, eindeutig pro Zeile. So identifizierst du jede Zeile sicher.
- Datentypen: `VARCHAR(60)` für kurzen Text mit Längengrenze, `TEXT` für langen Text, `TIMESTAMPTZ` für einen Zeitpunkt mit Zeitzone, `BIGINT` für große Ganzzahlen.
- Constraints (Regeln): `NOT NULL` erzwingt einen Wert, `UNIQUE` verbietet Doppelte, `DEFAULT now()` setzt automatisch den aktuellen Zeitpunkt.
- Index: `CREATE INDEX` macht das Suchen nach `name` schnell. Das WARUM steht im [Glossar](glossar-datenbank.md#index).

## Ausführen und prüfen

```
./knospe migrate
```

Der `MigrationRunner` führt nur die neue `004` aus, in einer eigenen Transaktion (siehe [Migrationen](04-migrationen-system.md)). Mit `php bin/console status` im Container siehst du, dass sie als erledigt vermerkt ist. Vertan? `./knospe migrate` und rückwärts `php bin/console rollback 1` helfen beim Üben.

Wie du die Tabelle jetzt abfragst, zeigt [SQL-Grundlagen](06-sql-basics.md).
