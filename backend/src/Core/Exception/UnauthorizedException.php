<?php

declare(strict_types=1);

namespace Knospe\Core\Exception;

use Throwable;

/**
 * Der Zugriff erfordert eine Anmeldung, es ist aber niemand angemeldet.
 * Ergibt HTTP 401.
 */
final class UnauthorizedException extends HttpException
{
    public function __construct(string $message = 'Anmeldung erforderlich', ?Throwable $previous = null)
    {
        parent::__construct(401, $message, $previous);
    }
}
