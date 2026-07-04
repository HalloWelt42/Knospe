# Schnellstart mit Docker

Docker ist der empfohlene Weg. Damit brauchst du weder PHP noch PostgreSQL selbst zu installieren - alles läuft in Containern.

## Voraussetzung

Docker muss installiert und gestartet sein. Prüfe im Terminal:

```
docker --version
```

Kommt eine Versionsnummer zurück, ist alles bereit. Falls nicht, installiere Docker über die offizielle Docker-Dokumentation für dein Betriebssystem.

## Einrichten und starten

Wechsle in den Projektordner und führe der Reihe nach aus:

```
./knospe setup
./knospe up
./knospe status
```

- `setup` erzeugt eine `.env` (falls noch keine existiert) und baut die Images. Beim ersten Mal dauert das etwas.
- `up` startet den Stack im Hintergrund und spielt ausstehende Migrationen ein.
- `status` zeigt den Zustand und - wichtig - die tatsächlichen Adressen.

## Die gezeigten Ports öffnen

Die Ausgabe von `status` sieht sinngemäß so aus:

```
  PHP-API :  http://localhost:8000/api/health
  Frontend:  http://localhost:5173
  Postgres:  localhost:15432  (DB knospe, User knospe)
```

Die Zahlen können bei dir anders lauten. Der Grund: `tools/setup-env.sh` leitet aus dem Projektpfad (per `cksum`) einen eindeutigen Projektnamen und freie Host-Ports ab und schreibt sie in die `.env`. So stören sich zwei Installationen nie. Verlass dich immer auf das, was `./knospe status` anzeigt, nicht auf feste Zahlen.

Öffne die genannte Frontend-Adresse im Browser. Zum Testen der API rufe die PHP-API-Adresse mit `/api/health` auf - sie sollte eine kurze Statusmeldung liefern. Anmelden kannst du dich mit dem Demo-Zugang `demo@knospe.local` und dem Passwort `passwort`.

## Erste Änderung

Ändere im Frontend eine Überschrift, etwa in `frontend/src/App.svelte`, und speichere. Der Dev-Server aktualisiert die Seite sofort. Backend-Änderungen greifen beim nächsten Aufruf, da PHP je Anfrage neu lädt.

## Weiter

- Ohne Docker: [Installation ohne Docker](03-installation-bare-metal.md)
- Was liegt wo: [Datei-Übersicht](04-datei-uebersicht.md)
