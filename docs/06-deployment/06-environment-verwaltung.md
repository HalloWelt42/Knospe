# Umgebungen verwalten

Software läuft selten nur an einem Ort. Ueblich sind drei Umgebungen: **Entwicklung** (auf deinem Rechner), **Test** (zum Ausprobieren) und **Produktion** (für echte Nutzer). Jede hat eigene Einstellungen. Knospe steuert das über eine einzige Datei - die [`.env`](../../.env).

## Eine Datei, viele Schalter

Die [`.env`](../../.env) hält alle umgebungsspezifischen Werte: Ports, Datenbank-Zugangsdaten, `APP_ENV` und `APP_DEBUG`. In Entwicklung steht dort `APP_DEBUG=true`, in Produktion `false` (siehe [Produktions-Checkliste](05-produktions-checkliste.md)). Die [`.env`](../../.env) ist gitignored - sie gehört dem jeweiligen Ort, nicht dem Repo. Als Vorlage dient die eingecheckte [`.env.example`](../../.env.example).

## Warum kollidieren zwei Installationen nie?

Ein besonderes Problem: Wenn du dasselbe Projekt zweimal auscheckst (etwa für zwei Aufgaben parallel), würden beide dieselben Ports und denselben Compose-Namen nutzen - und sich gegenseitig stören. Genau das verhindert [`tools/setup-env.sh`](../../tools/setup-env.sh).

Das Skript leitet Name und Ports aus dem **absoluten Projektpfad** ab. Es bildet einen Hash mit `cksum` und rechnet daraus einen Versatz:

```sh
HASH=$(printf '%s' "$ROOT_DIR" | cksum | awk '{print $1}')
OFFSET=$(( HASH % 400 ))
PHP_PORT=$(( 8000 + OFFSET ))
VITE_PORT=$(( 5173 + OFFSET ))
DB_PORT=$(( 15432 + OFFSET ))
```

Weil zwei verschiedene Ordner verschiedene Pfade haben, ergeben sich verschiedene Ports und ein eigener `COMPOSE_PROJECT_NAME`. Dadurch bekommt jede Installation eigene Container, ein eigenes Netz und ein eigenes Datenvolume - vollständig getrennt.

## Wichtige Regeln des Skripts

- Eine vorhandene [`.env`](../../.env) wird **nie** überschrieben. Deine Werte bleiben stabil.
- Geheimnisse (`APP_SECRET`, `POSTGRES_PASSWORD`) werden zufällig erzeugt.
- Es nutzt nur POSIX-Werkzeuge, läuft also überall.

## Welche Ports gelten gerade?

Nie raten - fragen:

```sh
./knospe status
```

Das zeigt die tatsächlichen Adressen aus der [`.env`](../../.env). Dieselben Werte braucht DataGrip als Postgres-Port. Mehr zum Compose-Bezug in [Docker Compose im Detail](01-docker-compose-detailliert.md).
