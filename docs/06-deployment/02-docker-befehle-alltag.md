# Docker im Alltag

Für den täglichen Betrieb gibt es das Skript `./knospe`. Es ist ein einziger Einstieg, der im Kern nur dokumentierte `docker compose`-Befehle aufruft. Nichts passiert versteckt - alles lässt sich auch von Hand ausführen. Genau das ist die Lernabsicht: Du siehst, was darunter liegt.

## Die wichtigsten Befehle

```sh
./knospe up        # Stack starten (im Hintergrund) und Migrationen ausführen
./knospe down      # Stack stoppen
./knospe status    # Adressen und Zustand anzeigen
./knospe logs php  # Logs eines Dienstes folgen
```

`./knospe status` ist der wichtigste Blick: Es liest die `.env` und zeigt die tatsächlichen Host-Ports (PHP-API, Frontend, Postgres). Weil die Ports pfad-eindeutig sind (siehe [Umgebungen verwalten](06-environment-verwaltung.md)), musst du nicht raten.

## Was darunter passiert

Im Skript steckt eine schlichte Hilfsfunktion:

```sh
dc() { docker compose "$@"; }
```

Damit wird jeder Befehl auf `docker compose` abgebildet. Beispiele, die du auch direkt eintippen kannst:

```sh
docker compose up -d        # entspricht ./knospe up
docker compose down         # entspricht ./knospe down
docker compose ps           # Teil von ./knospe status
docker compose logs -f php  # entspricht ./knospe logs php
```

## Shell im Container

Manchmal willst du direkt im Container arbeiten, etwa Composer oder das CLI aufrufen:

```sh
docker compose exec php sh
# im Container dann z.B.:
php bin/console status
composer test
```

Für einmalige Befehle reicht `exec` ohne Shell:

```sh
docker compose exec -T php php bin/console migrate
```

Genau so ruft auch `./knospe migrate` die Migrationen auf. Wenn etwas klemmt, hilft [Logs und Fehlersuche](03-logs-und-debugging.md).
