# Spickzettel: Docker

Knospe läuft komplett in Containern: PHP, PostgreSQL und die Werkzeuge. So brauchst du auf deinem Rechner nichts weiter zu installieren, und zwei Installationen stören sich nie. Meist genügt das Skript `./knospe`, darunter arbeitet Docker Compose.

## Compose-Befehle

| Befehl | Wirkung |
| --- | --- |
| `docker compose up -d` | Container im Hintergrund starten |
| `docker compose down` | Container stoppen und entfernen |
| `docker compose ps` | laufende Container zeigen |
| `docker compose logs -f` | Logs live mitlesen |
| `docker compose exec php bash` | Shell im PHP-Container öffnen |

Das `-d` steht für "detached" - der Start blockiert dein Terminal nicht.

## Images und Volumes

Ein Image ist die Bauvorlage, ein Container die laufende Instanz. Ein Volume speichert Daten dauerhaft, unabhängig vom Container-Leben.

| Befehl | Wirkung |
| --- | --- |
| `docker images` | vorhandene Images |
| `docker volume ls` | Volumes auflisten |
| `docker ps` | laufende Container |

Die PostgreSQL-Daten liegen in einem benannten Volume, nicht in einem Bind-Mount. Das ist persistent und auf macOS zuverlässig.

## ./knospe als Kurzform

Statt roher Docker-Befehle nutzt du im Alltag das Projekt-Skript:

| Kurzform | entspricht etwa |
| --- | --- |
| `./knospe up` | `docker compose up -d` |
| `./knospe down` | `docker compose down` |
| `./knospe logs` | `docker compose logs -f` |
| `./knospe status` | Status und tatsächliche Ports |

`./knospe status` ist wichtig: die Host-Ports werden pfad-eindeutig abgeleitet ([`tools/setup-env.sh`](../../../tools/setup-env.sh)), stehen also nicht fest. Hier siehst du, unter welchem Port deine Installation gerade läuft.

Vertiefung: [Betrieb mit Docker Compose](../../06-deployment/01-docker-compose-detailliert.md) und [Das knospe-Skript](../../06-deployment/02-docker-befehle-alltag.md). Referenz: die offizielle Docker-Dokumentation.
