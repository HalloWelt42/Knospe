<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Auth;

use Knospe\Http\Json;
use Knospe\Support\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Liefert das CSRF-Token der Sitzung. Die Oberflaeche ruft dies einmal beim
 * Start auf und schickt das Token danach bei veraendernden Anfragen mit.
 *
 * Lern mehr: docs/08-debugging-und-sicherheit/07-sicherheits-basics.md
 */
final class CsrfHandler implements RequestHandlerInterface
{
    public function __construct(private Session $session)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return Json::response(['token' => $this->session->csrfToken()]);
    }
}
