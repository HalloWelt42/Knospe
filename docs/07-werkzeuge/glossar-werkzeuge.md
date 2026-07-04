# Glossar: Werkzeuge

Kurze Erklärungen der Begriffe, die in diesem Kapitel vorkommen. Wenn dir beim Lesen ein Wort unklar ist, findest du es hier - mit Verweis auf die passende Anleitung.

## IDE

Die Abkürzung steht für "Integrated Development Environment", also integrierte Entwicklungsumgebung. Eine IDE vereint Editor, Codeverständnis, Testlauf, Debugger und Versionsverwaltung in einem Programm. Für Knospe nutzen wir PhpStorm. Einrichtung: [PhpStorm einrichten](01-phpstorm-installation.md).

## Interpreter

Das Programm, das PHP-Code tatsächlich ausführt. Bei Knospe liegt der PHP-8.5-Interpreter im Docker-Container, nicht auf dem Mac. Deshalb bindet die IDE ihn über Docker ein. Details: [PHP-Interpreter über Docker](02-phpstorm-docker-interpreter.md).

## Container

Eine abgeschlossene, mitgelieferte Umgebung, in der eine Anwendung mit allen Abhängigkeiten läuft. Knospe startet seine Container mit Docker Compose über das Skript `knospe`. Im Container liegt das Projekt unter `/app`.

## Debugger

Ein Werkzeug, das das laufende Programm anhalten und die Werte der Variablen zeigen kann. Knospe verwendet Xdebug 3 (Port 9003). So findest du Fehler durch Beobachten statt durch Raten. Details: [Debuggen mit Xdebug](04-xdebug-debugging.md).

## Breakpoint

Ein Haltepunkt an einer Codezeile. Erreicht das Programm diese Zeile im Debug-Betrieb, stoppt es dort, damit du den Zustand untersuchen kannst. Gesetzt wird er per Klick links neben die Zeile.

## Inspektion

Eine automatische Prüfung des Codes, die Fehler und Auffälligkeiten meldet, bevor du das Programm startest. Ergänzt wird sie durch die statische Analyse PHPStan (Level 6). Details: [Code-Inspektion](07-phpstorm-code-inspection.md).

## Path-Mapping

Die Zuordnung zwischen deinem lokalen Projektordner und dem Pfad im Container (`/app`). Ohne korrektes Mapping findet die IDE weder Tests noch Breakpoints. Siehe [PHP-Interpreter über Docker](02-phpstorm-docker-interpreter.md).

## Remote Interpreter

Ein Interpreter, der nicht lokal, sondern anderswo läuft - bei uns im Container. PHPUnit wird als "PHPUnit by Remote Interpreter" eingerichtet. Details: [PHPUnit in PhpStorm](03-phpunit-in-phpstorm.md).

## Datenquelle

In DataGrip die konfigurierte Verbindung zu einer Datenbank (Host, Port, Benutzer). Für Knospe: `localhost` plus der Port aus `./knospe status`. Details: [DataGrip verbinden](05-datagrip-verbindung.md).
