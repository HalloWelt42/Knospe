<?php

declare(strict_types=1);

namespace Knospe\Tests\Integration;

use Knospe\Domain\Post\PostgresPostRepository;
use Knospe\Tests\TestCase;

/**
 * Testet das Beitrags-Repository gegen die echte Datenbank. Alles läuft in
 * einer Transaktion, die am Ende zurückgerollt wird - es bleibt nichts zurück.
 * Lern mehr: docs/03-datenbank/09-repository-pattern.md
 */
final class PostgresPostRepositoryTest extends TestCase
{
    private function createUser(): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (email, display_name, password_hash) VALUES (?, ?, ?) RETURNING id',
        );
        $stmt->execute(['test-' . bin2hex(random_bytes(4)) . '@example.test', 'Testnutzer', 'x']);

        return (int) $stmt->fetchColumn();
    }

    public function testCreateAndFind(): void
    {
        $repository = new PostgresPostRepository($this->pdo);
        $userId = $this->createUser();

        $post = $repository->create($userId, 'Titel', 'Ein Text');

        self::assertGreaterThan(0, $post->id);

        $gefunden = $repository->findById($post->id);
        self::assertNotNull($gefunden);
        self::assertSame('Titel', $gefunden->title);
        self::assertSame('Testnutzer', $gefunden->authorName);
    }

    public function testUpdateAndDelete(): void
    {
        $repository = new PostgresPostRepository($this->pdo);
        $userId = $this->createUser();

        $post = $repository->create($userId, 'Alt', 'Alt');
        $repository->update($post->id, 'Neu', 'Neuer Text');

        $aktualisiert = $repository->findById($post->id);
        self::assertNotNull($aktualisiert);
        self::assertSame('Neu', $aktualisiert->title);

        $repository->delete($post->id);
        self::assertNull($repository->findById($post->id));
    }
}
