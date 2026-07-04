# Installation ohne Docker

Du kannst Knospe auch direkt auf deinem Rechner betreiben, ohne Container. Das ist mehr Handarbeit, hilft aber beim Verstehen, was Docker sonst für dich erledigt. Empfohlen ist trotzdem der [Docker-Weg](02-installation-docker.md).

## Was du brauchst

- PHP 8.5 mit den Erweiterungen `pdo_pgsql` und `mbstring`
- PostgreSQL 16 (Server läuft, du kannst eine Datenbank anlegen)
- Composer (verwaltet die PHP-Abhängigkeiten)
- Node.js mit npm (für Vite und die Svelte-Oberfläche)

Wie du diese Programme installierst, unterscheidet sich je nach Betriebssystem. Nutze auf macOS und Linux die jeweiligen Paketverwaltungen deines Systems; unter Windows bietet sich das Linux-Subsystem an. Halte dich an die offiziellen Anleitungen unter php.net und postgresql.org.

## Datenbank vorbereiten

Lege in PostgreSQL eine Datenbank und einen Benutzer an, zum Beispiel Datenbank `knospe`, Benutzer `knospe`. Merke dir das Passwort.

## Konfiguration (.env)

Kopiere die Vorlage und trage deine Werte ein:

```
cp .env.example .env
```

Wichtig sind Datenbank-Name, Benutzer, Passwort und der Datenbank-Host (bei lokaler Installation meist `localhost`). `backend/src/Support/Config` liest diese Werte aus der Umgebung.

## Backend starten

Installiere die Abhängigkeiten, spiele die Migrationen ein und starte den eingebauten PHP-Server auf das Verzeichnis mit dem Front-Controller:

```
cd backend
composer install
php bin/console migrate
php bin/console seed
php -S localhost:8000 -t public
```

Der Front-Controller ist `backend/public/index.php`. Über `php bin/console` läuft das CLI mit `migrate`, `rollback`, `status` und `seed`.

## Frontend starten

In einem zweiten Terminal:

```
cd frontend
npm install
npm run dev
```

Vite startet den Entwicklungsserver und leitet Aufrufe von `/api` an das Backend weiter. Öffne die von Vite genannte Adresse im Browser.

## Weiter

- Aufbau der Ordner: [Datei-Übersicht](04-datei-uebersicht.md)
- Wo finde ich was: [Projektstruktur verstehen](05-projektstruktur-bedeutung.md)
