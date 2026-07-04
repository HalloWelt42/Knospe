# Betrieb ohne Docker

Docker ist der bequeme Weg, aber Knospe braucht es nicht zwingend. Wer versteht, was der Container macht, kann alles auch direkt auf dem eigenen Rechner laufen lassen. Das heißt "bare metal": ohne Container, direkt auf dem System. Der Sinn dieser Anleitung ist Verständnis - du siehst, dass Docker nur ein Bequemlichkeitsmantel um ganz normale Werkzeuge ist.

## Voraussetzungen

- **PHP 8.5** mit den Erweiterungen `pdo_pgsql` und `mbstring`
- **PostgreSQL 16**
- **Composer** (für die PHP-Abhängigkeiten)
- **Node** (für das Vite-Frontend)

## Datenbank vorbereiten

Lege Datenbank und Benutzer an, passend zu deiner [`.env`](../../.env):

```sh
createdb knospe
createuser knospe --pwprompt
```

## Umgebungsvariablen (.env)

Ohne Docker musst du der PHP-Anwendung sagen, wo die Datenbank liegt. Der Servicename `postgres` existiert jetzt nicht - nimm `localhost`:

```sh
DB_HOST=localhost
DB_PORT=5432
DB_NAME=knospe
DB_USER=knospe
DB_PASSWORD=dein-passwort
APP_ENV=development
```

Die Klasse [`backend/src/Support/Config.php`](../../backend/src/Support/Config.php) liest diese Werte, [`backend/src/Database/Connection.php`](../../backend/src/Database/Connection.php) baut daraus die PDO-Verbindung.

## Backend starten

```sh
cd backend
composer install
php bin/console migrate   # Tabellen anlegen
php bin/console seed      # Demo-Daten (demo@knospe.local / passwort)
php -S localhost:8000 -t public public/index.php
```

Das ist exakt der Befehl aus dem Container - [`public/index.php`](../../backend/public/index.php) ist der Front-Controller.

## Frontend starten

```sh
cd frontend
npm install
npm run dev
```

Vite liefert die SPA aus und leitet `/api` an das Backend weiter.

## Hinweis für Produktion

Der eingebaute PHP-Server ist nur für Entwicklung gedacht. Für echten Betrieb setzt man einen Webserver mit PHP-FPM davor, liefert das gebaute Frontend (`npm run build`) statisch aus und schaltet Debug ab. Details in der [Produktions-Checkliste](05-produktions-checkliste.md).
