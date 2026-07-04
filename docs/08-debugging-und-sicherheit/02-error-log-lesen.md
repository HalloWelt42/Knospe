# Fehlerprotokolle lesen

Wenn eine Anfrage im Browser nur "Interner Serverfehler" zeigt, steckt der eigentliche Grund im Protokoll. Denn `ErrorHandlingMiddleware` gibt aus gutem Grund keine internen Details nach aussen - sie schreibt sie ins Log. Dort schaust du nach.

## Das Log ansehen

Weil das Backend in einem Container läuft, holst du die Ausgabe mit dem Verwaltungsskript:

```bash
./knospe logs php
```

Das zeigt den Ausgabestrom des PHP-Dienstes. Für laufende Beobachtung hängst du `-f` an (folgen), sofern das Skript es durchreicht, oder du wiederholst den Befehl nach der fehlerhaften Anfrage.

## Was du im Protokoll findest

Ein typischer Eintrag von Monolog sieht so aus:

```
[2026-07-04 12:03:11] app.ERROR: Call to a member function getId() on null
{"exception":"[object] (TypeError ... /app/backend/src/Domain/Post/Service.php:42)"}
```

Drei Informationen sind Gold wert:

- **Die Meldung**: was schiefging.
- **Datei und Zeile**: `Service.php:42` - genau dort setzt du an.
- **Der Stacktrace**: die Aufrufkette von oben nach unten. Ganz oben steht der Ort des Absturzes, darunter, wer diese Stelle aufgerufen hat. So verfolgst du den Weg zurück bis zu deinem eigenen Code.

## Pfade richtig lesen

Achtung auf die Pfade: Im Container heißt dein Projekt `/app`. Ein `/app/backend/src/...` im Log entspricht lokal `backend/src/...`. Dieses Mapping brauchst du auch für den Debugger, siehe [Breakpoints und Schrittausführung](05-breakpoints-und-stepping.md).

## Häufige Einträge

- `ValidationException` mit Statuscode 422: keine echte Panne, sondern abgelehnte Eingaben.
- `PDOException`: die Datenbank meckert, oft ein Tippfehler im SQL oder eine fehlende Migration.
- `TypeError`: ein Wert hatte den falschen Typ, häufig ein `null`, wo ein Objekt erwartet wurde.

Welche Fehlerarten es überhaupt gibt, klärt [Fehlertypen verstehen](01-fehlertypen-verstehen.md).
