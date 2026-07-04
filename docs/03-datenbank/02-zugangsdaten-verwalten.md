# Zugangsdaten verwalten

Passwörter, Geheimnisse und Ports gehören nicht in den Quellcode und nicht ins Git-Repository. Knospe löst das mit zwei Dateien: `.env.example` (eingecheckt) und `.env` (privat, gitignored). Das WARUM: Jeder soll sehen, welche Variablen es gibt, aber niemand soll fremde oder echte Geheimnisse mitliefern.

## Die Vorlage: .env.example

`.env.example` wird eingecheckt und dient als Dokumentation. Sie enthält Platzhalter, keine echten Werte:

```
COMPOSE_PROJECT_NAME=knospe
PHP_PORT=8000
VITE_PORT=5173
DB_PORT=15432
APP_ENV=development
APP_SECRET=bitte-beim-setup-erzeugen
POSTGRES_DB=knospe
POSTGRES_USER=knospe
POSTGRES_PASSWORD=bitte-beim-setup-erzeugen
```

## Die echte .env

Die echte `.env` erzeugt `./knospe setup` einmalig - mit einem pfad-eindeutigen Projektnamen, freien Ports und frisch gewürfelten Geheimnissen (`APP_SECRET`, `POSTGRES_PASSWORD`). Existiert die Datei schon, wird sie nie überschrieben. Sie steht in `.gitignore` und verlässt deinen Rechner nicht.

## Welche Variable wofür

- `COMPOSE_PROJECT_NAME` - trennt Container, Netz und Datenvolume von jeder anderen Installation.
- `PHP_PORT`, `VITE_PORT`, `DB_PORT` - die Host-Ports, die von aussen sichtbar sind. `./knospe status` zeigt die echten Werte.
- `APP_SECRET` - Geheimnis für Sitzungen und CSRF-Schutz.
- `POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD` - Datenbankname und Zugang. Diese liest sowohl der Postgres-Container als auch das Backend (siehe `backend/src/Support/Config.php`).

## Lokal gegen Produktion

Für die Entwicklung stehen `APP_ENV=development` und `APP_DEBUG=true` - Fehler werden ausführlich angezeigt. In der Produktion setzt du `APP_ENV=production` und `APP_DEBUG=false`, vergibst ein starkes, langes `APP_SECRET` und ein echtes Datenbank-Passwort. Die Struktur bleibt gleich, nur die Werte ändern sich - ein Vorteil des `.env`-Ansatzes.

Weiter geht es mit [PDO und sichere Abfragen](03-pdo-und-sql.md).
