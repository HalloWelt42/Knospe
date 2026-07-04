<?php

declare(strict_types=1);

namespace Knospe\Domain\Post;

use PDO;

/**
 * PostgreSQL-Umsetzung des Beitrags-Repositorys. Beim Lesen wird der Name
 * des Autors per JOIN mitgeholt. Alle Abfragen nutzen Prepared Statements.
 *
 * Lern mehr: ./docs/03-datenbank/03-pdo-und-sql.md
 */
final class PostgresPostRepository implements PostRepositoryInterface
{
    private const SELECT = 'SELECT p.*, u.display_name AS author_name
                            FROM posts p
                            JOIN users u ON u.id = p.author_id';

    public function __construct(private PDO $pdo)
    {
    }

    public function recent(int $limit = 50): array
    {
        $stmt = $this->pdo->prepare(self::SELECT . ' ORDER BY p.created_at DESC LIMIT ?');
        $stmt->execute([$limit]);

        return array_map(Post::fromRow(...), $stmt->fetchAll());
    }

    public function findById(int $id): ?Post
    {
        $stmt = $this->pdo->prepare(self::SELECT . ' WHERE p.id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row === false ? null : Post::fromRow($row);
    }

    public function create(int $authorId, string $title, string $body): Post
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO posts (author_id, title, body) VALUES (?, ?, ?) RETURNING id',
        );
        $stmt->execute([$authorId, $title, $body]);
        $id = (int) $stmt->fetchColumn();

        return $this->findByIdOrFail($id);
    }

    public function update(int $id, string $title, string $body): Post
    {
        $stmt = $this->pdo->prepare(
            'UPDATE posts SET title = ?, body = ?, updated_at = now() WHERE id = ?',
        );
        $stmt->execute([$title, $body, $id]);

        return $this->findByIdOrFail($id);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare('DELETE FROM posts WHERE id = ?')->execute([$id]);
    }

    private function findByIdOrFail(int $id): Post
    {
        $post = $this->findById($id);
        if ($post === null) {
            throw new \RuntimeException("Beitrag {$id} nicht gefunden nach Schreibvorgang.");
        }

        return $post;
    }
}
