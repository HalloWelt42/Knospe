<?php

declare(strict_types=1);

namespace Knospe\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Die Middleware-Pipeline (PSR-15).
 *
 * Idee: Eine Anfrage läuft durch mehrere Middleware (z.B. Fehlerbehandlung,
 * später Auth), erreicht am Ende den Kern-Handler (den Router) und die
 * Antwort läuft denselben Weg zurück. Die Kette wird von hinten nach vorne
 * gefaltet - so bleibt die Reihenfolge intuitiv (erste Middleware zuerst).
 *
 * Lern mehr: ../../../docs/02-architektur/06-middleware-pipeline.md
 */
final class Pipeline implements RequestHandlerInterface
{
    /**
     * @param RequestHandlerInterface $core       Der Kern-Handler am Ende (Router)
     * @param list<MiddlewareInterface> $middleware In Ausführungsreihenfolge
     */
    public function __construct(
        private RequestHandlerInterface $core,
        private array $middleware = [],
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $handler = array_reduce(
            array_reverse($this->middleware),
            static fn (RequestHandlerInterface $next, MiddlewareInterface $mw): RequestHandlerInterface
                => new MiddlewareHandler($mw, $next),
            $this->core,
        );

        return $handler->handle($request);
    }
}
