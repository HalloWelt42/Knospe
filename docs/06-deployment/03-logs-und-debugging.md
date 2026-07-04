# Logs und Fehlersuche

Wenn etwas nicht funktioniert, ist die erste Frage immer: Was sagen die Logs? In Knospe gibt es drei Orte, an denen du nachschaust - Backend, Datenbank und Browser. Wer diese drei kennt, findet fast jeden Fehler schnell.

## Backend-Logs (PHP)

```sh
./knospe logs php
```

Das folgt der Ausgabe des php-Dienstes. Unser Kern nutzt Monolog und schreibt nach `stderr`. Docker sammelt `stderr` ein, deshalb landen PHP-Fehler und geloggte Ereignisse direkt in diesem Strom. Bei `APP_DEBUG=true` liefert die `ErrorHandlingMiddleware` ([`backend/src/Http/Middleware/ErrorHandlingMiddleware.php`](../../backend/src/Http/Middleware/ErrorHandlingMiddleware.php)) zusätzlich eine ausführliche JSON-Fehlermeldung an den Client. In Produktion steht dort nur eine knappe Meldung, die Details bleiben im Log (siehe [Produktions-Checkliste](05-produktions-checkliste.md)).

## Datenbank-Logs (PostgreSQL)

```sh
./knospe logs postgres
```

Hier siehst du, ob Postgres bereit ist (der Healthcheck ruft `pg_isready`), und ob Verbindungen oder Abfragen scheitern. Ein häufiger Anfängerfehler: Die API startet, bevor die Datenbank oben ist. Genau dafür wartet der php-Dienst per `depends_on` auf den Healthcheck (siehe [Docker Compose im Detail](01-docker-compose-detailliert.md)).

## Alle Dienste zusammen

Ohne Dienstnamen folgt `./knospe logs` allen dreien gleichzeitig - gut, um das Zusammenspiel beim Start zu sehen:

```sh
./knospe logs
```

## Browser: Konsole und Netzwerk

Das Frontend ist eine Svelte-SPA. Fehler dort siehst du nicht im Backend, sondern in den Entwicklerwerkzeugen des Browsers:

- **Konsole**: JavaScript-Fehler, Warnungen, eigene `console.log`-Ausgaben.
- **Netzwerk**: Jeder API-Aufruf mit Statuscode. Ein `403` deutet oft auf ein fehlendes CSRF-Token hin (erst `GET /api/csrf` holen, dann Header `X-CSRF-Token` senden). Ein `401` heißt: nicht angemeldet.

Mit diesen vier Blicken - PHP, Postgres, Konsole, Netzwerk - grenzt du fast jedes Problem sauber ein.
