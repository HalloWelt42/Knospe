# Debugging-Strategien

Werkzeuge allein finden keinen Fehler - eine Vorgehensweise schon. Fehlersuche ist Detektivarbeit: Du stellst eine Vermutung auf und prüfst sie, bis nur eine Erklärung übrig bleibt. Ein paar bewährte Strategien führen dich dorthin.

## Erwartung gegen Realität stellen

Der erste Schritt ist immer: Was soll passieren, und was passiert wirklich? Formuliere beides ausdrücklich. "Nach POST /api/posts sollte 201 kommen, es kommt aber 422." Diese eine Beobachtung engt die Suche schon stark ein - 422 ist eine `ValidationException`, also stimmt etwas mit den Eingaben nicht, siehe [Exceptions und Fehler](04-exceptions-vs-errors.md).

## Halbierung

Läuft der Fehler durch viele Schritte - Frontend ruft [`src/lib/api.ts`](../../frontend/src/lib/api.ts), das trifft den Router, der einen Handler, der einen Service, der das Repository - dann teile die Strecke. Prüfe in der Mitte: Kommt die Anfrage überhaupt im Handler an? Ist die Datenbankabfrage schuld oder schon der Aufruf davor? Mit jeder Halbierung verwirfst du die halbe Suchfläche. Das ist der schnellste Weg durch eine lange Kette.

## Minimaler Reproduktionsfall

Ein Fehler, den du zuverlässig auslösen kannst, ist halb gelöst. Reduziere die Bedingungen, bis nur das Nötigste übrig bleibt: Welche eine Anfrage, welcher eine Datensatz löst ihn aus? Ein Aufruf mit `curl` gegen den lokalen Port aus `./knospe status` ist oft klarer als der Weg durch die Oberfläche:

```bash
curl -i http://localhost:<port>/api/posts/999
```

Wenn du den Fehler auf Kommando erzeugst, kannst du auch prüfen, ob deine Korrektur wirklich wirkt.

## Einen Testfall schreiben

Der beste Reproduktionsfall ist ein automatischer Test. Das Projekt bringt PHPUnit mit (`tests/Unit`, `tests/Integration`); die Basis-`TestCase` läuft in einer Transaktion mit Rollback, die Datenbank bleibt also sauber. Schreib einen Test, der den Fehler zeigt (rot), behebe die Ursache, sieh ihn grün werden - und er bewacht die Stelle für immer:

```bash
composer test
```

## Die Reihenfolge

Beobachten, halbieren, reproduzieren, absichern. Erst wenn du den Fehler siehst und benennst, greifst du zu Log ([Fehlerprotokolle lesen](02-error-log-lesen.md)) oder Debugger ([Breakpoints und Schrittausführung](05-breakpoints-und-stepping.md)). Rate nie ins Blaue - miss.
