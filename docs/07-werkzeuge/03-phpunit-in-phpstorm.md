# PHPUnit in PhpStorm

Knospe hat automatisierte Tests unter `backend/tests/` (Unit und Integration). Auf der Kommandozeile startest du sie mit `composer test`. Bequemer ist es aber, einzelne Tests direkt in der IDE per Klick zu starten - inklusive grüner oder roter Anzeige und Sprung zur Fehlerstelle. Dafür sagen wir PhpStorm, dass es PHPUnit im Container ausführen soll.

## Warum im Container

Die Tests brauchen dieselbe PHP-Version und dieselbe Datenbankverbindung wie die Anwendung. Beides liegt im Container. Wenn PhpStorm die Tests dort ausführt, testest du genau das, was auch produktiv läuft. Das setzt den in [PHP-Interpreter über Docker](02-phpstorm-docker-interpreter.md) eingerichteten Interpreter voraus.

## Einrichtung

1. Oeffne "Settings > Sprachen & Frameworks > PHP > Test Frameworks".
2. Klicke auf das Plus und wähle "PHPUnit by Remote Interpreter". "Remote" meint hier den Docker-Interpreter, den du zuvor angelegt hast.
3. Wähle diesen Interpreter aus.
4. Setze bei "Use Composer autoloader" den Autoloader-Pfad auf `/app/backend/vendor/autoload.php`.
5. Setze bei "Use alternative configuration file" die Datei `/app/backend/phpunit.xml`.

Beachte: Alle Pfade sind Container-Pfade, beginnen also mit `/app`, nicht mit deinem Mac-Pfad.

## Tests starten

Oeffne eine Testklasse unter `backend/tests/`, zum Beispiel einen Test im Ordner `tests/Unit`. Neben der Klasse und neben jeder Testmethode erscheint ein grünes Pfeil-Symbol. Ein Klick darauf startet genau diesen Test.

Die Integrationstests nutzen eine Basisklasse, die jeden Test in einer Transaktion kapselt und danach zurückrollt. So bleibt die Datenbank sauber, egal wie oft du einen Test startest.

Unten öffnet sich das Test-Fenster mit Baumansicht. Gruen bedeutet bestanden, rot zeigt die Zeile mit der fehlgeschlagenen Zusicherung - ein Klick springt dorthin. Damit wird Testen zu einem schnellen Werkzeug beim Verstehen und Aendern des Codes.
