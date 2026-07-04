<?php

declare(strict_types=1);

namespace Knospe\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Verbindet eine einzelne Middleware mit dem nächsten Glied der Kette.
 * Ein Baustein, aus dem die Pipeline die gesamte Kette zusammensetzt.
 *
 * Lern mehr: ./docs/02-architektur/06-middleware-pipeline.md
 */
final class MiddlewareHandler implements RequestHandlerInterface
{
    public function __construct(
        private MiddlewareInterface $middleware,
        private RequestHandlerInterface $next,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request, $this->next);
    }
}
