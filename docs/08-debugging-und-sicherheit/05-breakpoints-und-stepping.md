# Breakpoints und Schrittausführung

Der Debugger ist das mächtigste Werkzeug zur Fehlersuche, weil er dein Programm einfriert und dich hineinschauen lässt - ohne eine einzige Zeile Code zu ändern. Grundlage im Projekt ist Xdebug 3.

## Einmalige Einrichtung

Xdebug lauscht auf **Port 9003** (nicht 9000, das ist die häufigste Stolperfalle). In PhpStorm richtest du den Interpreter über den Docker-Compose-Interpreter ein und legst ein **Path-Mapping** an: dein lokaler Projektordner entspricht `/app` im Container. Ohne dieses Mapping findet der Debugger deine Dateien nicht. Die vollständige Einrichtung steht in `docs/07-werkzeuge/` (PhpStorm, Xdebug). Wichtig sind außerdem `xdebug.start_with_request` und `client_host=host.docker.internal`, damit der Container den Debugger auf deinem Rechner erreicht.

## Ein Breakpoint

Ein **Breakpoint** ist eine Marke an einer Codezeile. Du setzt sie per Klick auf den linken Rand, zum Beispiel in `backend/src/Domain/Post/Service.php`. Erreicht die Anfrage diese Zeile, hält das Programm an - und du siehst alle Variablen mit ihren aktuellen Werten.

## Schritt für Schritt weitergehen

Ist das Programm angehalten, steuerst du den Ablauf:

- **Step Over**: die aktuelle Zeile ausführen und zur nächsten springen. Aufgerufene Funktionen laufen durch, ohne dass du hineingehst.
- **Step Into**: in die aufgerufene Funktion hineingehen, um deren Innenleben zu verfolgen.
- **Step Out**: die aktuelle Funktion zu Ende laufen lassen und zur aufrufenden Stelle zurückkehren.

So folgst du dem Weg einer Anfrage durch Router, Middleware und Service.

## Bedingte Breakpoints und Watches

Wenn nur ein bestimmter Fall interessiert, gib dem Breakpoint eine **Bedingung**, etwa `$id === 42`. Dann hält er nur genau dann. Ein **Watch** ist ein Ausdruck, den du dauerhaft beobachtest - zum Beispiel `count($posts)` -, dessen Wert sich bei jedem Schritt aktualisiert.

## Wann statt var_dump

Sobald du mehr als einen Wert prüfen willst oder den Ablauf nicht durchschaust, ist der Debugger schneller als wiederholte Ausgaben. Den Vergleich zieht [var_dump vs. Debugger](03-var-dump-vs-debug.md). Für eine systematische Suche hilft [Debugging-Strategien](06-debugging-strategien.md).
