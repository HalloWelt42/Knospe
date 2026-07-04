<?php

declare(strict_types=1);

namespace Knospe\Support;

/**
 * Duenner Wrapper um die Passwort-Funktionen von PHP.
 *
 * Wichtig:
 *  - Passwoerter werden mit Argon2id gehasht (absichtlich langsam, damit
 *    Ausprobieren teuer wird). Niemals md5/sha1/sha256 fuer Passwoerter.
 *  - verify() sollte immer aufgerufen werden, auch wenn es den Nutzer nicht
 *    gibt (gegen Timing-Rueckschluesse) - siehe UserService::authenticate().
 *
 * Lern mehr: docs/08-debugging-und-sicherheit/08-passwort-sicherheit.md
 */
final class PasswordHasher
{
    public static function hash(string $plain): string
    {
        return password_hash($plain, PASSWORD_ARGON2ID);
    }

    public static function verify(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }
}
