<?php

declare(strict_types=1);

namespace Knospe\Http\Middleware;

use Knospe\Core\Exception\HttpException;
use Knospe\Support\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Schutz vor Cross-Site-Request-Forgery.
 *
 * Bei verändernden Methoden (POST/PUT/PATCH/DELETE) muss der Header
 * "X-CSRF-Token" mit dem Token der Sitzung übereinstimmen. Die Oberfläche
 * holt das Token einmal über GET /api/csrf und schickt es danach mit.
 * Lesende Anfragen (GET) sind ausgenommen.
 *
 * Lern mehr: ./docs/08-debugging-und-sicherheit/10-cors-verstehen.md
 */
final class CsrfMiddleware implements MiddlewareInterface
{
    private const UNSAFE = ['POST', 'PUT', 'PATCH', 'DELETE'];

    public function __construct(private Session $session)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), self::UNSAFE, true)) {
            $sent = $request->getHeaderLine('X-CSRF-Token');
            $expected = $this->session->csrfToken();

            if ($sent === '' || !hash_equals($expected, $sent)) {
                throw new HttpException(403, 'Ungültiges oder fehlendes CSRF-Token.');
            }
        }

        return $handler->handle($request);
    }
}
