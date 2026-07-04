# Produktions-Checkliste

Entwicklung und Produktion sind zwei verschiedene Welten. In der Entwicklung darf alles bequem und gesprächig sein; in Produktion zählen Sicherheit und Stabilität. Diese Liste sammelt, was du umstellen solltest, bevor Knospe öffentlich erreichbar ist. Der Sinn: verstehen, warum jeder Punkt wichtig ist.

## Debug ausschalten

```sh
APP_ENV=production
APP_DEBUG=false
```

Bei `APP_DEBUG=false` liefert die `ErrorHandlingMiddleware` keine Stacktraces mehr an den Client - Details würden sonst Angreifern helfen. Fehler bleiben im Log (siehe [Logs und Fehlersuche](03-logs-und-debugging.md)).

## Verschlüsselung (HTTPS)

Ohne HTTPS reisen Session-Cookie und CSRF-Token im Klartext. Setze einen Reverse-Proxy mit TLS-Zertifikat davor. Das Session-Cookie ist bereits `HttpOnly` und `SameSite=Lax`; unter HTTPS kommt sinnvollerweise das `Secure`-Flag dazu.

## Netzwerk absichern

- Nur die nötigen Ports öffnen. Der Postgres-Port (`DB_PORT`) gehört **nicht** ins offene Internet - eine Firewall oder Bindung an `localhost` schützt ihn.
- Der eingebaute PHP-Server ist Entwicklungswerkzeug; in Produktion gehört PHP-FPM hinter einen echten Webserver (siehe [Betrieb ohne Docker](04-bare-metal-installation.md)).

## Sichere Header

Setze am Webserver Schutz-Header, etwa `Content-Security-Policy`, `X-Content-Type-Options: nosniff` und `Referrer-Policy`. Sie kosten nichts und wehren ganze Angriffsklassen ab.

## Rate-Limiting

Begrenze Anfragen pro IP, besonders an `POST /api/auth/login`. Das bremst Passwort-Rateangriffe. Die Passwörter selbst liegen bereits als Argon2id-Hash vor ([`backend/src/Support/PasswordHasher.php`](../../backend/src/Support/PasswordHasher.php)).

## Secrets sauber halten

`APP_SECRET` und `POSTGRES_PASSWORD` erzeugt das Setup zufällig. Die [`.env`](../../.env) ist gitignored - checke sie nie ein. In Produktion setzt man Secrets über die Umgebung des Hosts, nicht als Datei im Repo.

## Backups einrichten

```sh
./knospe backup
```

Das schreibt einen komprimierten `pg_dump` nach `./backups`. Plane das regelmäßig (etwa per Cronjob) und teste die Wiederherstellung mit `./knospe restore <datei>`. Ein Backup, das nie zurückgespielt wurde, ist nur eine Hoffnung.
