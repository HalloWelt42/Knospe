# Debuggen mit Xdebug

Beim Debuggen hältst du das Programm an einer bestimmten Zeile an und schaust dir an, welche Werte die Variablen gerade haben. Das ist oft viel aufschlussreicher als Ausgaben ins Log zu streuen. Knospe bringt dafür Xdebug 3 mit; du musst PhpStorm nur richtig damit verbinden.

## Der wichtige Port: 9003, nicht 9000

Xdebug 3 lauscht standardmäßig auf Port 9003. Das ist die häufigste Stolperfalle: Aeltere Anleitungen nennen Port 9000, doch der gilt nicht mehr. Stelle in "Settings > PHP > Debug" den "Debug port" auf 9003. Stimmt der Port nicht, passiert beim Debuggen schlicht nichts - kein Fehler, nur Stille.

## Konfiguration im Container

Im Container ist Xdebug bereits so eingestellt:

- `xdebug.mode=debug`
- `xdebug.start_with_request=yes` - dadurch startet jede Anfrage automatisch eine Debug-Sitzung, du musst nichts extra auslösen.
- `xdebug.client_host=host.docker.internal` - so findet Xdebug aus dem Container heraus deinen Mac, auf dem PhpStorm horcht. Der Name `host.docker.internal` ist die Brücke vom Container zum Host.

## Erste Sitzung

1. Klicke in PhpStorm oben rechts auf den Telefonhörer "Start Listening for PHP Debug Connections". Ohne dieses Zuhören nimmt die IDE keine Verbindung an.
2. Setze einen Breakpoint: Klicke links neben eine Codezeile, zum Beispiel in einem Handler unter `backend/src/Http/Handler/`. Es erscheint ein roter Punkt.
3. Löse die Anfrage aus, etwa mit einem Aufruf von `GET /api/health` oder `GET /api/posts` im Browser oder Frontend.

Das Programm hält an der Zeile an. Unten siehst du alle Variablen. Mit den Schritt-Tasten gehst du weiter: "Step Over" (Zeile für Zeile), "Step Into" (in eine Funktion hinein), "Step Out" (wieder heraus).

Wenn nichts anhält, prüfe zuerst den Port (9003), dann ob du wirklich zuhörst, und ob das Path-Mapping lokal <-> `/app` aus [PHP-Interpreter über Docker](02-phpstorm-docker-interpreter.md) stimmt.
