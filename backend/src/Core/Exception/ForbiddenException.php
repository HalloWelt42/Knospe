<?php

declare(strict_types=1);

namespace Knospe\Core\Exception;

use Throwable;

/**
 * Angemeldet, aber ohne Recht für diese Aktion (z.B. fremder Beitrag).
 * Ergibt HTTP 403.
 */
final class ForbiddenException extends HttpException
{
    public function __construct(string $message = 'Kein Zugriff', ?Throwable $previous = null)
    {
        parent::__construct(403, $message, $previous);
    }
}
