# Docker Compose im Detail

Der ganze Stack von Knospe steckt in einer einzigen Datei: `docker-compose.yml`. Sie beschreibt drei Dienste, die zusammen die Anwendung ausmachen. Warum Compose? Weil man so mit einem Befehl (`./knospe up`) alles startet, ohne PHP, PostgreSQL und Node von Hand installieren zu müssen.

## Die drei Dienste

- **php** baut aus `docker/php/Dockerfile` ein eigenes Image und startet den eingebauten PHP-Server: `php -S 0.0.0.0:8000 -t public public/index.php`. Der Front-Controller `backend/public/index.php` fängt jede Anfrage ab und leitet sie durch Router und Pipeline.
- **postgres** nutzt das fertige Image `postgres:16-alpine` - unsere Datenbank.
- **node** nutzt `node:20-alpine` und startet Vite (`npm run dev`) für das Svelte-Frontend.

## Ports und Name aus der .env

Container-intern sind die Ports fest (8000, 5432, 5173). Nach aussen werden sie über die `.env` gemappt:

```yaml
ports:
  - "${PHP_PORT:-8000}:8000"
```

`COMPOSE_PROJECT_NAME`, `PHP_PORT`, `VITE_PORT` und `DB_PORT` erzeugt `tools/setup-env.sh` pfad-eindeutig. So stören sich zwei Installationen nie. Mehr dazu in [Umgebungen verwalten](06-environment-verwaltung.md).

## Benanntes Volume

Die Datenbank liegt in einem benannten Volume, nicht in einem Bind-Mount:

```yaml
volumes:
  - pgdata:/var/lib/postgresql/data
```

Das ist persistent und unter macOS stabil (siehe [Glossar: Betrieb](glossar-betrieb.md)).

## Healthcheck und extra_hosts

Der `healthcheck` prüft mit `pg_isready`, ob Postgres wirklich bereit ist. Der php-Dienst wartet über `depends_on: condition: service_healthy` darauf. So startet die API nie ins Leere. Mit `extra_hosts` wird `host.docker.internal` gesetzt - nötig für Xdebug unter Linux und praktisch für den Dev-Proxy.
