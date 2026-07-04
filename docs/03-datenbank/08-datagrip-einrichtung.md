# DataGrip einrichten

DataGrip ist ein Werkzeug, mit dem du direkt in die Datenbank schaust: Tabellen erkunden, SQL ausprobieren, Ergebnisse als Tabelle sehen. Das ist beim Lernen sehr hilfreich, weil du sofort siehst, was deine [Migrationen](04-migrationen-system.md) und Abfragen bewirken.

## Den richtigen Port herausfinden

Die Datenbank läuft im Docker-Container und ist von außen nur über einen pfad-eindeutigen Host-Port erreichbar. Frag ihn zuerst ab:

```
./knospe status
```

Dort steht der aktuelle Wert von `DB_PORT` (in der Vorlage `15432`, bei dir wahrscheinlich anders). Denselben Wert findest du in deiner [`.env`](../../.env).

## Neue PostgreSQL-Datenquelle anlegen

In DataGrip: Datenquelle hinzufügen und PostgreSQL wählen. Dann eintragen:

- Host: `localhost`
- Port: der Wert aus `./knospe status` (zum Beispiel `15432`)
- Datenbank: der Wert von `POSTGRES_DB` (Standard `knospe`)
- Benutzer: `POSTGRES_USER` (Standard `knospe`)
- Passwort: `POSTGRES_PASSWORD` aus deiner [`.env`](../../.env)

Warum `localhost` und nicht `postgres`? Der Servicename `postgres` gilt nur im Docker-Netz. Von deinem Mac aus gehst du über den gemappten Host-Port auf `localhost`. Mehr dazu unter [Zugangsdaten verwalten](02-zugangsdaten-verwalten.md).

## Verbindung testen und Schema erkunden

Nach dem Test-Klick sollte die Verbindung grün sein. Im Baum links siehst du die Tabellen `users`, `posts` und - falls das Kommentar-Plugin aktiviert ist - `comments`, dazu die Verwaltungstabelle `schema_migrations`. Klapp eine Tabelle auf: Spalten, Datentypen und Fremdschlüssel werden sichtbar - genau die aus deinen Migrationen.

## Ausprobieren

Oeffne eine Query-Konsole und tippe eine Abfrage aus [SQL-Grundlagen](06-sql-basics.md):

```sql
SELECT id, title, created_at FROM posts ORDER BY created_at DESC;
```

Ausführen, und die Ergebnisse erscheinen als Tabelle. So bekommst du ein Gefühl dafür, was in der Datenbank wirklich steht.
