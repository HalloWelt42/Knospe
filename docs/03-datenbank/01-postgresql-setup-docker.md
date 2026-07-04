# PostgreSQL mit Docker

Knospe braucht eine Datenbank, aber du sollst sie nicht selbst auf deinem Rechner installieren müssen. Stattdessen läuft PostgreSQL in einem Docker-Container - definiert in `docker-compose.yml`. Das WARUM: So bekommt jede Person exakt dieselbe Datenbank-Version, und dein System bleibt sauber.

## Der Dienst im Compose

```yaml
postgres:
  image: postgres:16-alpine
  environment:
    POSTGRES_DB: ${POSTGRES_DB:-knospe}
    POSTGRES_USER: ${POSTGRES_USER:-knospe}
    POSTGRES_PASSWORD: ${POSTGRES_PASSWORD:-knospe}
  ports:
    - "${DB_PORT:-15432}:5432"
  volumes:
    - pgdata:/var/lib/postgresql/data
  healthcheck:
    test: ["CMD-SHELL", "pg_isready -U ${POSTGRES_USER:-knospe} -d ${POSTGRES_DB:-knospe}"]
    interval: 3s
    timeout: 3s
    retries: 20
```

`postgres:16-alpine` ist das offizielle, schlanke Abbild in Version 16. Die Zugangsdaten liest Docker aus deiner `.env` (siehe [Zugangsdaten verwalten](02-zugangsdaten-verwalten.md)).

## Healthcheck - warum das wichtig ist

Der Container ist gestartet, aber die Datenbank braucht noch einen Moment, bis sie Anfragen annimmt. Der `healthcheck` prüft mit `pg_isready`, ob sie wirklich bereit ist. Das Backend wartet darauf: In der `php`-Definition steht `depends_on: postgres: condition: service_healthy`. So läuft keine Migration ins Leere.

## Benanntes Volume statt Bind-Mount

Die Zeile `pgdata:/var/lib/postgresql/data` legt die Daten in ein von Docker verwaltetes, benanntes Volume. Kein Ordner auf deiner Platte. Das ist bewusst so: Ein Bind-Mount für Postgres-Daten kann auf macOS zu Datei-Fehlern führen. Ein benanntes Volume ist persistent und macOS-sicher - deine Beiträge überleben ein `./knospe restart`, und ein `./knospe reset` löscht sie gezielt.

## Der Host-Port

`${DB_PORT:-15432}:5432` bedeutet: Container-intern spricht Postgres immer auf `5432`, nach aussen wird ein pfad-eindeutiger Port aus der `.env` gemappt. Den echten Wert zeigt dir `./knospe status`. Diesen Port brauchst du für [DataGrip](08-datagrip-einrichtung.md).
