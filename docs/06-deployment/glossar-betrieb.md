# Glossar: Betrieb

Rund um Docker und den Betrieb tauchen immer dieselben Begriffe auf. Wer sie einmal sauber versteht, liest die anderen Kapitel dieses Ordners viel leichter. Hier stehen sie kurz und in eigenen Worten erklärt.

## Container

Ein Container ist eine abgeschottete, laufende Umgebung für einen Dienst - etwa PHP oder PostgreSQL. Er enthält genau die Software, die dieser Dienst braucht, und stört nichts anderes auf deinem System. In Knospe laufen drei Container: php, postgres und node (siehe [Docker Compose im Detail](01-docker-compose-detailliert.md)).

## Image

Ein Image ist die Vorlage, aus der ein Container entsteht - vergleichbar mit einem Bauplan. Aus einem Image lassen sich beliebig viele gleiche Container starten. Knospe baut das php-Image selbst (aus `docker/php/Dockerfile`) und nutzt fertige Images für Postgres und Node.

## Volume

Ein Volume ist ein von Docker verwalteter Speicherort, der Container-Neustarts überlebt. Ohne Volume wären die Datenbankdaten beim nächsten `down` verloren. Knospe nutzt ein **benanntes** Volume (`pgdata`) statt eines Bind-Mounts auf einen Projektordner - das ist persistent und unter macOS zuverlässig.

## Bind-Mount

Beim Bind-Mount wird ein echter Ordner deines Rechners in den Container gespiegelt. Praktisch für Quellcode (Knospe spiegelt so das Projekt nach `/app`), aber für Datenbankdateien unter macOS unzuverlässig - deshalb dort das benannte Volume.

## Netzwerk

Compose legt für das Projekt ein eigenes Netzwerk an. Darin erreichen sich die Container über ihren Dienstnamen: Das Backend spricht die Datenbank schlicht als `postgres:5432` an, ohne Host-Port. Die Host-Ports aus der `.env` sind nur für den Zugriff von außen (siehe [Umgebungen verwalten](06-environment-verwaltung.md)).

## Healthcheck

Ein Healthcheck ist eine wiederkehrende Prüfung, ob ein Dienst wirklich bereit ist - nicht nur gestartet. Knospe prüft Postgres mit `pg_isready`. Der php-Dienst wartet über `depends_on` auf diesen gesunden Zustand, damit die API nie startet, bevor die Datenbank antworten kann.

## Front-Controller

Kein Docker-Begriff, aber zentral: `backend/public/index.php` ist der eine Einstiegspunkt, durch den jede Anfrage läuft, bevor der Router sie verteilt.
