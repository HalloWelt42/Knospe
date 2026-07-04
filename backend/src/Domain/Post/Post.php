<?php

declare(strict_types=1);

namespace Knospe\Domain\Post;

/**
 * Ein Beitrag als reine Datenklasse. authorName ist optional und wird beim
 * Lesen aus einer Verknüpfung (JOIN mit users) mitgefüllt, damit die
 * Oberfläche den Namen zeigen kann, ohne extra nachzufragen.
 *
 * Lern mehr: ../../../../docs/01-grundlagen/01-oop-klassen-intro.md
 */
final readonly class Post
{
    public function __construct(
        public int $id,
        public int $authorId,
        public string $title,
        public string $body,
        public string $createdAt,
        public ?string $authorName = null,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            authorId: (int) $row['author_id'],
            title: (string) $row['title'],
            body: (string) $row['body'],
            createdAt: (string) $row['created_at'],
            authorName: isset($row['author_name']) ? (string) $row['author_name'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'authorId' => $this->authorId,
            'authorName' => $this->authorName,
            'title' => $this->title,
            'body' => $this->body,
            'createdAt' => $this->createdAt,
        ];
    }
}
