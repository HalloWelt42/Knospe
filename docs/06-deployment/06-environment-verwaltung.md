# Umgebungen verwalten

Software läuft selten nur an einem Ort. Ueblich sind drei Umgebungen: **Entwicklung** (auf deinem Rechner), **Test** (zum Ausprobieren) und **Produktion** (für echte Nutzer). Jede hat eigene Einstellungen. Knospe steuert das über eine einzige Datei - die [`.env`](../../.env).

## Eine Datei, viele Schalter

Die [`.env`](../../.env) hält alle umgebungsspezifischen Werte: Ports, Datenbank-Zugangsdaten, `APP_ENV` und `APP_DEBUG`. In Entwicklung steht dort `APP_DEBUG=true`, in Produktion `false` (siehe [Produktions-Checkliste](05-produktions-checkliste.md)). Die [`.env`](../../.env) ist gitignored - sie gehört dem jeweiligen Ort, nicht dem Repo. Als Vorlage dient die eingecheckte [`.env.example`](../../.env.example).

## Eigener Name per Postfix

Beim ersten `./knospe setup` fragt Knospe nach einem **Postfix** für den Projektnamen:

```
Projekt-Postfix wählen (Enter = automatisch '904798'):
```

Gibst du etwa `aufgabe-a` ein, heisst das Projekt `knospe-aufgabe-a`. Mit Enter nimmt Knospe automatisch einen kurzen Wert aus dem Pfad-Hash. Ohne Nachfrage geht es auch: `./knospe setup aufgabe-a`.

## Kollisionsprüfung

Bevor die [`.env`](../../.env) geschrieben wird, prüft [`tools/setup-env.sh`](../../tools/setup-env.sh) auf Kollisionen:

- **Name:** Gibt es bereits ein Docker-Projekt mit diesem Namen (Container, Volume oder Netz)? Dann wird der Postfix abgelehnt - so teilst du dir nie versehentlich dieselbe Datenbank mit einer anderen Installation.
- **Ports:** Ist ein vorgesehener Host-Port belegt? Dann zählt das Skript zum nächsten freien Port hoch.

## Woher kommen die Ports?

Die Startwerte leitet das Skript aus dem **absoluten Projektpfad** ab (Hash per `cksum`), damit zwei Ordner automatisch verschiedene Werte bekommen:

```sh
HASH=$(printf '%s' "$ROOT_DIR" | cksum | awk '{print $1}')
OFFSET=$(( HASH % 400 ))
PHP_PORT=$(( 8000 + OFFSET ))    # danach auf "frei" geprüft
VITE_PORT=$(( 5173 + OFFSET ))
DB_PORT=$(( 15432 + OFFSET ))
```

So bekommt jede Installation eigene Container, ein eigenes Netz und ein eigenes Datenvolume - vollständig getrennt.

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
