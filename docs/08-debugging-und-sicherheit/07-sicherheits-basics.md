# Sicherheits-Grundlagen

Sicherheit ist kein einzelnes Feature, sondern eine Haltung, die durch den ganzen Code zieht. Die gute Nachricht: Die meisten Angriffe zielen auf immer dieselben Schwachstellen. Wer diese kennt, deckt den Grossteil ab. Hier ein Überblick, jeweils mit dem Ort in Knospe, an dem es gelöst ist.

## Injection

Wenn Eingaben in eine Sprache (SQL, Shell) eingefügt werden, kann Angreifercode mitreisen. Gegenmittel: Prepared Statements. Werte werden nie in den String geklebt, sondern als Parameter übergeben.

```php
// so, nicht per String-Verkettung
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
$stmt->execute(['id' => $id]);
```

Siehe `src/Domain/Post/PostgresRepository.php`.

## XSS (Cross-Site-Scripting)

Fremder HTML/JS-Code landet in der Ausgabe und läuft im Browser des Opfers. Gegenmittel: Ausgabe escapen. Unser Frontend ist eine Svelte-SPA - Svelte escapet Text in `{ausdruck}` automatisch. Vorsicht nur bei `{@html ...}`.

## CSRF (Cross-Site-Request-Forgery)

Eine fremde Seite löst im Namen des eingeloggten Nutzers eine verändernde Anfrage aus. Gegenmittel: ein Token, das nur die echte Oberfläche kennt. Siehe `src/Http/Middleware/CsrfMiddleware.php` und [CORS verstehen](10-cors-verstehen.md).

## Auth und Autorisierung

Zwei Fragen: Wer bist du (Authentifizierung, Session-Cookie) und was darfst du (Autorisierung, Rollenprüfung). Beides gehört getrennt geprüft.

## Secrets

Passwörter, Schlüssel und Datenbank-Zugänge kommen aus der Umgebung (`.env` via `src/Support/Config.php`), niemals fest in den Code.

## Faustregeln

- Traue keiner Eingabe, auch nicht aus dem eigenen Frontend.
- Escape spät, validiere früh.
- Speichere nie im Klartext, was gehasht gehört.

Details je Thema in [Passwörter sicher speichern](08-passwort-sicherheit.md), [Logging richtig](12-logging-best-practices.md) und im [Glossar](glossar-sicherheit.md).
