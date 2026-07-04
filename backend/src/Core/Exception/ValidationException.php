<?php

declare(strict_types=1);

namespace Knospe\Core\Exception;

use Throwable;

/**
 * Eingaben sind ungueltig. Ergibt HTTP 422 und traegt die Feldfehler mit,
 * damit die Oberflaeche gezielt Rueckmeldung geben kann.
 *
 * Lern mehr: docs/08-debugging-und-sicherheit/07-sicherheits-basics.md
 */
final class ValidationException extends HttpException
{
    /**
     * @param array<string, string> $errors Feldname => Meldung
     */
    public function __construct(
        string $message = 'Eingabe ungueltig',
        public readonly array $errors = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct(422, $message, $previous);
    }
}
