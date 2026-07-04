# Quellen und Links

Diese Liste sammelt gute Anlaufstellen zum Weiterlernen. Wo es geht, sind deutschsprachige Seiten bevorzugt, weil das den Einstieg erleichtert. Jeder Eintrag sagt kurz, wofür er nützlich ist und ab welchem Niveau er sich lohnt. Die offiziellen Handbücher sind meist präzise, aber knapp - lies sie ergänzend zu unserer eigenen Doku wie [dem Glossar](../glossar.md).

## PHP

- <https://www.php.net/manual/de/> - Deutschsprachiges PHP-Handbuch, Nachschlagewerk für Sprache und Funktionen, ab Einsteiger geeignet.
- <https://www.php.net/manual/de/language.oop5.php> - Objektorientierung in PHP (Klassen, Interfaces, Enums), passend zu `src/Domain/`, ab Grundkenntnissen.
- <https://www.php.net/manual/de/book.pdo.php> - PDO-Datenbankschicht, Grundlage von [`src/Database/Connection.php`](../../backend/src/Database/Connection.php), mittleres Niveau.
- <https://www.php-fig.org/psr/> - PSR-Standards (Autoloading, HTTP-Handler), erklärt die Basis unseres Kerns, fortgeschritten.

## Frontend (Svelte, Vite, TypeScript)

- <https://svelte.dev/docs> - Offizielle Svelte-Doku inklusive der Runes `$state`, `$derived`, `$effect`, ab Einsteiger.
- <https://svelte.dev/tutorial> - Interaktives Svelte-Tutorial zum Ausprobieren im Browser, ideal für den Anfang.
- <https://vite.dev/guide/> - Vite-Handbuch zu Dev-Server und Build, erklärt unseren Dev-Proxy `/api`, mittleres Niveau.
- <https://www.typescriptlang.org/docs/> - TypeScript-Doku für Typen und Interfaces im Frontend, ab Grundkenntnissen.

## Datenbank

- <https://www.postgresql.org/docs/16/> - PostgreSQL-16-Handbuch, unsere Datenbankversion, für Nachschlagen von SQL und Typen, mittleres Niveau.
- <https://www.postgresql.org/docs/16/tutorial.html> - Einführendes PostgreSQL-Tutorial, guter Start für erste Abfragen.

## Betrieb und Werkzeuge

- <https://docs.docker.com/> - Docker-Doku zu Images, Volumes und Compose, passend zu unserem `./knospe`-Skript, ab Grundkenntnissen.
- <https://getcomposer.org/doc/> - Composer-Handbuch für Abhängigkeiten und Skripte wie `test`, mittleres Niveau.
- <https://docs.phpunit.de/> - PHPUnit-Doku für Unit- und Integrationstests unter `tests/`, mittleres Niveau.
- <https://phpstan.org/user-guide/getting-started> - PHPStan-Leitfaden zur statischen Analyse auf Level 6, fortgeschritten.

## Web-Grundlagen

- <https://developer.mozilla.org/de/docs/Web/HTTP> - Deutschsprachige HTTP-Grundlagen (Methoden, Statuscodes, Cookies), erklären Auth und CSRF, ab Einsteiger.
- <https://developer.mozilla.org/de/docs/Web/JavaScript> - JavaScript-Referenz auf Deutsch als Basis für TypeScript und Svelte, ab Einsteiger.
