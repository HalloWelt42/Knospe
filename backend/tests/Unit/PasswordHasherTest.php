<?php

declare(strict_types=1);

namespace Knospe\Tests\Unit;

use Knospe\Support\PasswordHasher;
use PHPUnit\Framework\TestCase;

/**
 * Testet das Passwort-Hashing.
 * Lern mehr: docs/08-debugging-und-sicherheit/08-passwort-sicherheit.md
 */
final class PasswordHasherTest extends TestCase
{
    public function testHashIsNotPlainText(): void
    {
        $hash = PasswordHasher::hash('geheim123');

        self::assertNotSame('geheim123', $hash);
        self::assertStringStartsWith('$argon2id$', $hash);
    }

    public function testVerifyAcceptsCorrectPassword(): void
    {
        $hash = PasswordHasher::hash('geheim123');

        self::assertTrue(PasswordHasher::verify('geheim123', $hash));
    }

    public function testVerifyRejectsWrongPassword(): void
    {
        $hash = PasswordHasher::hash('geheim123');

        self::assertFalse(PasswordHasher::verify('falsch', $hash));
    }
}
