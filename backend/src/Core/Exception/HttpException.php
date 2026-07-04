<?php

declare(strict_types=1);

namespace Knospe\Core\Exception;

use RuntimeException;
use Throwable;

/**
 * Basis für erwartbare HTTP-Fehler. Trägt den passenden Statuscode,
 * damit die zentrale Fehler-Middleware daraus eine saubere Antwort baut.
 *
 * Lern mehr: ./docs/02-architektur/07-exception-handling-zentral.md
 */
class HttpException extends RuntimeException
{
    public function __construct(
        public readonly int $statusCode,
        string $message = '',
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
