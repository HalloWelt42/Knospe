# Exceptions und Fehler

In PHP gibt es zwei Familien von Ausnahmen: `Error` und `Exception`. Der Unterschied ist keine Formsache, sondern eine Denkweise - er trennt das Unplanbare vom Planbaren.

## Planbar oder unplanbar

- **Error** signalisiert einen Programmierfehler: ein `TypeError`, eine unbekannte Klasse. So etwas soll gar nicht erst passieren. Du fängst es normalerweise nicht ab, du behebst die Ursache.
- **Exception** signalisiert eine erwartbare Ausnahmesituation: eine Eingabe ist ungültig, eine Ressource fehlt. Damit rechnest du und reagierst gezielt darauf.

## throw, try und catch

Einen erwartbaren Fehler löst du mit `throw` aus und fängst ihn mit `try`/`catch`:

```php
try {
    $post = $this->service->create($input);
} catch (ValidationException $e) {
    // gezielt auf ungültige Eingaben reagieren
    return Json::response(['felder' => $e->errors], 422);
}
```

## Eigene Exception-Klassen: die HttpException-Familie

Das Projekt zeigt genau diese Idee vor. Statt überall Statuscodes zu verstreuen, gibt es sprechende Klassen in `backend/src/Core/Exception/`:

```php
class HttpException extends RuntimeException
{
    public function __construct(
        public readonly int $statusCode,
        string $message = '',
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
```

Darauf bauen `NotFoundException` (404), `MethodNotAllowedException` (405), `UnauthorizedException` (401), `ForbiddenException` (403) und `ValidationException` (422) auf. Jede trägt ihren Statuscode selbst. Wirfst du im Handler eine `NotFoundException`, musst du dich um die HTTP-Antwort nicht mehr kümmern.

## Ein Ort fängt alles

Der Vorteil: Du wirfst die passende Exception dort, wo der Fehler entsteht, und die zentrale `ErrorHandlingMiddleware` in `backend/src/Http/Middleware/` verwandelt sie in eine saubere JSON-Antwort - mit dem richtigen Code, ohne interne Details nach aussen. Nur echte, unerwartete `Throwable` werden zu einem protokollierten 500er.

Diese Trennung erklärt das Kapitel [Fehlertypen verstehen](01-fehlertypen-verstehen.md) genauer. Die Architektur dahinter steht in `docs/02-architektur/07-exception-handling-zentral.md`.
