<?php

declare(strict_types=1);

namespace Knospe\Tests\Unit;

use Knospe\Core\Exception\ForbiddenException;
use Knospe\Core\Exception\ValidationException;
use Knospe\Domain\Post\Post;
use Knospe\Domain\Post\PostRepositoryInterface;
use Knospe\Domain\Post\PostService;
use Knospe\Plugin\Hook\HookDispatcher;
use PHPUnit\Framework\TestCase;

/**
 * Testet die Geschäftslogik für Beiträge - ohne Datenbank, mit einem
 * einfachen Test-Repository. So bleibt der Test schnell und fokussiert.
 * Lern mehr: ../../../docs/02-architektur/05-services-pattern.md
 */
final class PostServiceTest extends TestCase
{
    private function fakeRepository(?Post $vorhanden = null): PostRepositoryInterface
    {
        return new class ($vorhanden) implements PostRepositoryInterface {
            public function __construct(private ?Post $vorhanden)
            {
            }

            public function recent(int $limit = 50): array
            {
                return $this->vorhanden === null ? [] : [$this->vorhanden];
            }

            public function findById(int $id): ?Post
            {
                return $this->vorhanden;
            }

            public function create(int $authorId, string $title, string $body): Post
            {
                return new Post(1, $authorId, $title, $body, '2026-01-01 00:00:00');
            }

            public function update(int $id, string $title, string $body): Post
            {
                return new Post($id, 1, $title, $body, '2026-01-01 00:00:00');
            }

            public function delete(int $id): void
            {
            }
        };
    }

    public function testCreateRejectsEmptyTitle(): void
    {
        $service = new PostService($this->fakeRepository(), new HookDispatcher());

        $this->expectException(ValidationException::class);
        $service->create(1, '   ', 'Text');
    }

    public function testCreateFiresHook(): void
    {
        $hooks = new HookDispatcher();
        $service = new PostService($this->fakeRepository(), $hooks);

        $gerufen = null;
        $hooks->addAction('post.created', static function (Post $post) use (&$gerufen): void {
            $gerufen = $post->title;
        });

        $service->create(1, 'Titel', 'Text');

        self::assertSame('Titel', $gerufen);
    }

    public function testUpdateForbiddenForNonOwner(): void
    {
        $post = new Post(5, 1, 'Alt', 'Alt', '2026-01-01 00:00:00');
        $service = new PostService($this->fakeRepository($post), new HookDispatcher());

        $this->expectException(ForbiddenException::class);
        $service->update(5, 2, 'Neu', 'Neu');
    }
}
