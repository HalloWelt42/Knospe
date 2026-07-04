# PhpStorm einrichten

PhpStorm ist die Entwicklungsumgebung (IDE), mit der wir Knospe komfortabel bearbeiten. Eine IDE ist mehr als ein Editor: Sie versteht den Code, springt zu Definitionen, warnt vor Fehlern und bindet Docker, Tests und Debugger ein. Genau das brauchen wir, um den kleinen PHP-Kern von Knospe zu verstehen, statt nur Text zu lesen.

## Projekt öffnen

Oeffne PhpStorm und wähle "Open". Navigiere zum Projektordner `~/Entwicklung/Knospe` und bestätige. Wichtig: Immer den obersten Projektordner öffnen, nicht nur `backend/` oder `frontend/`. So sieht die IDE beide Seiten (PHP-Backend und Svelte-Frontend) sowie das Verwaltungsskript `knospe` und die [`docker-compose.yml`](../../docker-compose.yml).

Beim ersten Öffnen fragt PhpStorm oft, ob es Composer-Abhängigkeiten indexieren soll. Sage ja - dadurch kennt die IDE die Pakete aus [`backend/composer.json`](../../backend/composer.json) (etwa nyholm/psr7, monolog) und kann sinnvoll ergänzen.

## Code-Stil festlegen

Damit dein Code zur Hausnorm passt, stellst du unter "Settings > Editor > Code Style > PHP" den PSR-12-Stil ein. Knospe formatiert ohnehin mit PHP-CS-Fixer nach PSR-12 (siehe [PHP-CS-Fixer nutzen](08-php-cs-fixer-in-phpstorm.md)), aber ein passender IDE-Stil vermeidet unnötige Änderungen beim Tippen.

Der Ordner `.idea/` mit deinen persönlichen Einstellungen ist bewusst per [`.gitignore`](../../.gitignore) ausgeschlossen. So kann jede Person eigene Einstellungen haben, ohne das Repository zu verändern.

## Erster Überblick

Nutze die Projektansicht links, um dich zu orientieren:

- `backend/src/` - der PHP-Kern (Container, Router, Domänen). Startpunkt ist [`backend/public/index.php`](../../backend/public/index.php).
- `frontend/src/` - die Svelte-5-Oberfläche.
- `knospe` - das Verwaltungsskript für Docker (`./knospe up`, `status`, `logs`).

Als Nächstes verbinden wir die IDE mit dem laufenden Container, siehe [PHP-Interpreter über Docker](02-phpstorm-docker-interpreter.md).
