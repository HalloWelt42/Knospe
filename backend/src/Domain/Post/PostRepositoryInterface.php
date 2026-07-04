<?php

declare(strict_types=1);

namespace Knospe\Domain\Post;

/**
 * Vertrag für den Beitrags-Datenzugriff.
 * Lern mehr: ./docs/03-datenbank/09-repository-pattern.md
 */
interface PostRepositoryInterface
{
    /**
     * @return list<Post>
     */
    public function recent(int $limit = 50): array;

    public function findById(int $id): ?Post;

    public function create(int $authorId, string $title, string $body): Post;

    public function update(int $id, string $title, string $body): Post;

    public function delete(int $id): void;
}
