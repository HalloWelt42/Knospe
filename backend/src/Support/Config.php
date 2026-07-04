<?php

declare(strict_types=1);

namespace Knospe\Support;

/**
 * Zentrale, typisierte Konfiguration - gefuellt aus Umgebungsvariablen.
 *
 * Statt ueberall verstreut getenv() aufzurufen, gibt es ein einziges
 * Objekt mit klar benannten, typsicheren Werten. Das ist leichter zu
 * lesen, zu testen und zu aendern.
 *
 * Lern mehr: docs/02-architektur/10-konfiguration-und-umgebung.md
 */
final class Config
{
    public function __construct(
        public readonly string $appEnv,
        public readonly bool $appDebug,
        public readonly string $dbHost,
        public readonly int $dbPort,
        public readonly string $dbName,
        public readonly string $dbUser,
        public readonly string $dbPassword,
    ) {
    }

    /**
     * Baut die Konfiguration aus den Umgebungsvariablen.
     */
    public static function fromEnv(): self
    {
        return new self(
            appEnv: self::str('APP_ENV', 'production'),
            appDebug: self::bool('APP_DEBUG', false),
            dbHost: self::str('DB_HOST', 'postgres'),
            dbPort: (int) self::str('DB_PORT', '5432'),
            dbName: self::str('DB_NAME', 'knospe'),
            dbUser: self::str('DB_USER', 'knospe'),
            dbPassword: self::str('DB_PASSWORD', ''),
        );
    }

    private static function str(string $key, string $default): string
    {
        $value = $_ENV[$key] ?? getenv($key);

        return ($value === false || $value === '')
            ? $default
            : (string) $value;
    }

    private static function bool(string $key, bool $default): bool
    {
        $value = self::str($key, $default ? 'true' : 'false');

        return in_array(strtolower($value), ['1', 'true', 'yes', 'on'], true);
    }
}
