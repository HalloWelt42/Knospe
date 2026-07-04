# Middleware-Pipeline

Manche Aufgaben betreffen jede Anfrage: Fehler abfangen, das CSRF-Token prüfen, später vielleicht Protokollierung. Solche Querschnittsaufgaben will man nicht in jeden Handler kopieren. Dafür gibt es die Middleware-Pipeline nach PSR-15. Jede Middleware hängt sich zwischen Anfrage und Handler, macht ihre Sache und reicht dann weiter - oder bricht ab.

In Knospe wird die Kette im `Kernel` zusammengestellt:

```php
$pipeline = new Pipeline($router, [
    new ErrorHandlingMiddleware($logger, $this->config->appDebug),
    new CsrfMiddleware($this->container->get(Session::class)),
]);
```

Die **Reihenfolge** ist entscheidend. Die `ErrorHandlingMiddleware` steht ganz vorne, damit sie wirklich jeden Fehler dahinter einfangen kann - auch einen aus der CSRF-Prüfung. Danach kommt die `CsrfMiddleware`, ganz am Ende der `Router` als Kern-Handler.

Wie wird aus der Liste eine Kette? In `backend/src/Core/Pipeline.php` wird sie von hinten nach vorne gefaltet:

```php
$handler = array_reduce(
    array_reverse($this->middleware),
    static fn ($next, $mw) => new MiddlewareHandler($mw, $next),
    $this->core,
);
```

So bleibt die intuitive Reihenfolge erhalten: Die erste Middleware in der Liste läuft zuerst, die Antwort kommt dieselbe Kette zurück.

Eine eigene Middleware zu schreiben ist einfach - du implementierst `MiddlewareInterface` mit der Methode `process`:

```php
final class RequestLogMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        // vorher: etwas mit der Anfrage tun
        $response = $handler->handle($request);
        // nachher: etwas mit der Antwort tun
        return $response;
    }
}
```

Der Aufruf `$handler->handle($request)` reicht an die nächste Stufe weiter. Alles davor passiert auf dem Hinweg, alles danach auf dem Rückweg. Willst du früh abbrechen (etwa bei fehlender Anmeldung), gibst du einfach eine eigene Antwort zurück oder wirfst eine Exception, ohne `handle` aufzurufen.

Ein Blick auf die konkrete Fehler-Middleware: [Zentrale Fehlerbehandlung](07-exception-handling-zentral.md). Der Router als Kette-Ende: [Der Weg einer Anfrage](03-request-flow-diagramm.md).
