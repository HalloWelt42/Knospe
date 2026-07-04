# Fehlertypen verstehen

Bevor du einen Fehler beheben kannst, musst du wissen, welche Art von Fehler dir PHP meldet. Die Meldung selbst verrät dir das fast immer. Wer die Kategorien kennt, spart sich langes Suchen.

## Die vier klassischen Kategorien

- **Parse Error** (Syntaxfehler): Der Code lässt sich gar nicht erst lesen. Ein fehlendes Semikolon, eine nicht geschlossene Klammer. Nichts vom Skript läuft, weil PHP schon beim Einlesen scheitert. Beispiel: `PHP Parse error: syntax error, unexpected token`.
- **Fatal Error**: Der Code ist lesbar, aber etwas Unmögliches passiert zur Laufzeit - eine Methode auf `null` aufgerufen, eine unbekannte Klasse benutzt. Das Skript bricht an dieser Stelle ab. In modernem PHP sind das meist `Error`-Objekte wie `TypeError` oder `Error: Call to a member function on null`.
- **Warning**: Etwas ging schief, aber PHP macht weiter. Zum Beispiel eine Datei, die nicht gelesen werden konnte. Ignoriere Warnungen nie - sie deuten oft auf den eigentlichen Fehler weiter unten hin.
- **Notice / Deprecation**: Ein Hinweis, dass etwas unsauber ist (ein undefinierter Array-Schlüssel) oder in Zukunft wegfällt. Kein Abbruch, aber ein Grund zum Aufräumen.

## Typische Anfängerfehler

Ein Klassiker ist der Zugriff auf einen fehlenden Array-Schlüssel:

```php
$data = ['name' => 'Anna'];
echo $data['email']; // Warning: Undefined array key "email"
```

Sicherer mit dem Null-Coalescing-Operator:

```php
echo $data['email'] ?? 'unbekannt';
```

## Die Meldung richtig lesen

Jede Fehlermeldung nennt drei Dinge: den **Typ**, die **Aussage** und den **Ort** (Datei und Zeile). Lies immer von rechts nach links: Erst wo, dann was. Bei `TypeError ... in /app/backend/src/Domain/Post/Service.php on line 42` springst du direkt zu Zeile 42.

Wie du diese Meldungen im laufenden Betrieb findest, zeigt [Fehlerprotokolle lesen](02-error-log-lesen.md). Wie das Projekt erwartbare Fehler sauber in Antworten verwandelt, steht in [Exceptions und Fehler](04-exceptions-vs-errors.md).
