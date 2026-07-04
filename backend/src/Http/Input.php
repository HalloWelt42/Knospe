<?php

declare(strict_types=1);

namespace Knospe\Http;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Kleiner Helfer, um den JSON-Rumpf einer Anfrage als Array zu lesen.
 * Gibt bei fehlerhaftem oder leerem Rumpf ein leeres Array zurück.
 *
 * Lern mehr: ./docs/04-frontend/03-api-aufrufe-fetch.md
 */
final class Input
{
    /**
     * @return array<string, mixed>
     */
    public static function json(ServerRequestInterface $request): array
    {
        $decoded = json_decode((string) $request->getBody(), true);

        return is_array($decoded) ? $decoded : [];
    }
}
