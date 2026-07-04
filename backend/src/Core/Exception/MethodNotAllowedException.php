<?php

declare(strict_types=1);

namespace Knospe\Core\Exception;

use Throwable;

/**
 * Der Pfad passt, aber nicht die HTTP-Methode. Ergibt HTTP 405.
 */
final class MethodNotAllowedException extends HttpException
{
    public function __construct(string $message = 'Methode nicht erlaubt', ?Throwable $previous = null)
    {
        parent::__construct(405, $message, $previous);
    }
}
