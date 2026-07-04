# PHP-Interpreter über Docker

Knospe läuft in Containern. Auf deinem Mac ist gar kein passender PHP-Interpreter installiert, sondern nur im Docker-Container (PHP 8.5). Damit PhpStorm Code korrekt versteht, Tests startet und den Debugger anbindet, muss die IDE genau diesen Interpreter im Container benutzen. Das ist der wichtigste Einrichtungsschritt.

## Warum überhaupt

Würde PhpStorm einen lokalen oder gar keinen Interpreter verwenden, würden Versionsfunktionen, Erweiterungen (etwa pdo_pgsql) und der Autoloader nicht zu dem passen, was tatsächlich läuft. Der Container ist die Wahrheit - also lassen wir die IDE dort hineinschauen.

## Schritt für Schritt

Starte die Umgebung zuerst mit `./knospe up`, damit der Container läuft.

1. Oeffne "Settings > Sprachen & Frameworks > PHP" (englisch: "Languages & Frameworks > PHP").
2. Klicke bei "CLI Interpreter" auf das Zahnrad-Symbol und dann auf das Plus.
3. Wähle "From Docker, Vagrant, VM, WSL, Remote..." und darin den Typ "Docker Compose".
4. Wähle als Configuration file die [`docker-compose.yml`](../../docker-compose.yml) von Knospe und als Service den PHP-Dienst (in Knospe der Dienst `php`).
5. Setze den Lifecycle auf "Connect to existing container" - so nutzt PhpStorm den bereits laufenden Container, statt bei jedem Aufruf einen neuen zu starten.

## Path-Mapping prüfen

Innerhalb des Containers liegt das Projekt unter `/app`. PhpStorm muss wissen, dass dein lokaler Ordner `~/Entwicklung/Knospe` dem Container-Pfad `/app` entspricht. Dieses Path-Mapping (lokal <-> `/app`) wird meist automatisch erkannt; prüfe es dennoch, denn ohne korrektes Mapping findet die IDE weder Tests noch Breakpoints.

Nach dem Speichern zeigt PhpStorm die erkannte PHP-Version an. Steht dort 8.5, ist die Verbindung korrekt.

Auf dieser Basis richten wir als Nächstes die Tests ein, siehe [PHPUnit in PhpStorm](03-phpunit-in-phpstorm.md), und später den Debugger, siehe [Debuggen mit Xdebug](04-xdebug-debugging.md).
