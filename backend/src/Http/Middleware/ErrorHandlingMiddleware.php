<?php

declare(strict_types=1);

namespace Knospe\Http\Middleware;

use Knospe\Core\Exception\HttpException;
use Knospe\Core\Exception\ValidationException;
use Knospe\Http\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Fängt alle Fehler zentral ab und macht daraus saubere JSON-Antworten.
 *
 *  - ValidationException -> 422 mit Feldfehlern
 *  - HttpException       -> passender Statuscode (404, 405, ...)
 *  - alles andere        -> 500, protokolliert, ohne interne Details
 *    (nur bei APP_DEBUG=true werden Details mitgegeben)
 *
 * Diese Middleware steht ganz vorne in der Pipeline, damit sie wirklich
 * jeden Fehler dahinter einfangen kann.
 *
 * Lern mehr: ../../../../docs/02-architektur/07-exception-handling-zentral.md
 */
final class ErrorHandlingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private bool $debug = false,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            return Json::response(
                ['status' => 'fehler', 'meldung' => $e->getMessage(), 'felder' => $e->errors],
                $e->statusCode,
            );
        } catch (HttpException $e) {
            return Json::response(
                ['status' => 'fehler', 'meldung' => $e->getMessage()],
                $e->statusCode,
            );
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            $payload = ['status' => 'fehler', 'meldung' => 'Interner Serverfehler'];

            if ($this->debug) {
                $payload['debug'] = [
                    'typ' => $e::class,
                    'meldung' => $e->getMessage(),
                    'ort' => $e->getFile() . ':' . $e->getLine(),
                ];
            }

            return Json::response($payload, 500);
        }
    }
}
