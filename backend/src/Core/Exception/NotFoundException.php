<?php

declare(strict_types=1);

namespace Knospe\Core\Exception;

use Throwable;

/**
 * Wird geworfen, wenn keine Route (oder Ressource) passt. Ergibt HTTP 404.
 */
final class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Nicht gefunden', ?Throwable $previous = null)
    {
        parent::__construct(404, $message, $previous);
    }
}
