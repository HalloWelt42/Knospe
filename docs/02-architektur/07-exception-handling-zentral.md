# Zentrale Fehlerbehandlung

Fehler passieren überall: Eine Route fehlt, eine Eingabe ist ungültig, ein Nutzer darf etwas nicht. Würde jeder Handler seine Fehler selbst in HTTP-Antworten übersetzen, wäre das viel doppelter Code und leicht inkonsistent. Knospe macht es anders: Die Fachschichten werfen einfach passende Exceptions, und eine einzige Middleware macht daraus saubere JSON-Antworten.

Dafür gibt es eine kleine Familie von Fehlerklassen in `backend/src/Core/Exception/`. Basis ist `HttpException` - sie trägt den passenden Statuscode:

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

Die Nachfahren setzen den Code schon fest: `NotFoundException` ergibt 404, `MethodNotAllowedException` 405, `UnauthorizedException` 401, `ForbiddenException` 403. Die `ValidationException` trägt zusätzlich die Feldfehler und ergibt 422. Ein Service wirft also einfach das Richtige, ohne HTTP zu kennen - zum Beispiel `throw new NotFoundException("Beitrag {$id} nicht gefunden.")`.

Eingefangen wird alles in der `ErrorHandlingMiddleware` (siehe [Middleware-Pipeline](06-middleware-pipeline.md)):

```php
try {
    return $handler->handle($request);
} catch (ValidationException $e) {
    return Json::response(['status' => 'fehler', 'meldung' => $e->getMessage(), 'felder' => $e->errors], $e->statusCode);
} catch (HttpException $e) {
    return Json::response(['status' => 'fehler', 'meldung' => $e->getMessage()], $e->statusCode);
} catch (Throwable $e) {
    $this->logger->error($e->getMessage(), ['exception' => $e]);
    return Json::response(['status' => 'fehler', 'meldung' => 'Interner Serverfehler'], 500);
}
```

Wichtig ist der letzte Fall: Ein unerwarteter Fehler wird protokolliert, aber nach aussen gibt es nur eine neutrale 500 ohne interne Details. Kein Stacktrace, kein Dateipfad, kein SQL - solche Informationen gehören nicht in eine öffentliche Antwort. Nur wenn `APP_DEBUG=true` gesetzt ist, kommen Details für die Entwicklung mit. Das steuert die Konfiguration, siehe [Konfiguration und Umgebung](10-konfiguration-und-umgebung.md).

Der Effekt: Handler und Services bleiben kurz und ehrlich. Sie beschreiben nur, was schieflief - der Rest passiert an einer Stelle.
