<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Auth;

use Knospe\Http\Json;
use Knospe\Support\AuthContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Gibt den aktuell angemeldeten Nutzer zurueck (oder null). Die Oberflaeche
 * fragt das beim Start, um zu wissen, ob jemand angemeldet ist.
 */
final class MeHandler implements RequestHandlerInterface
{
    public function __construct(private AuthContext $auth)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return Json::response(['user' => $this->auth->user()?->toArray()]);
    }
}
