<?php

declare(strict_types=1);

namespace Knospe\Http;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Kleiner Helfer, um aus Daten eine JSON-Antwort zu bauen.
 * Hält die Handler kurz und sorgt für einheitliche Header und Umlaute.
 *
 * Lern mehr: ./docs/02-architektur/09-restful-apis-kurz.md
 */
final class Json
{
    /**
     * @param array<string, string> $headers Zusätzliche Header
     */
    public static function response(mixed $data, int $status = 200, array $headers = []): ResponseInterface
    {
        $body = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT,
        );

        return new Response(
            $status,
            ['Content-Type' => 'application/json; charset=utf-8'] + $headers,
            $body === false ? '{}' : $body,
        );
    }
}
