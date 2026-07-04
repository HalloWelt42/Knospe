<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Auth;

use Knospe\Http\Json;
use Knospe\Support\AuthContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Abmeldung: beendet die Sitzung.
 */
final class LogoutHandler implements RequestHandlerInterface
{
    public function __construct(private AuthContext $auth)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->auth->logout();

        return Json::response(['status' => 'abgemeldet']);
    }
}
