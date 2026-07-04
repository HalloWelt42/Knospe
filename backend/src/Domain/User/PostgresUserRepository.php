<?php

declare(strict_types=1);

namespace Knospe\Domain\User;

use PDO;

/**
 * PostgreSQL-Umsetzung des Nutzer-Repositorys. Nutzt ausschließlich
 * Prepared Statements (Schutz vor SQL-Injection).
 *
 * Lern mehr: ./docs/03-datenbank/03-pdo-und-sql.md
 */
final class PostgresUserRepository implements UserRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row === false ? null : User::fromRow($row);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        return $row === false ? null : User::fromRow($row);
    }

    public function create(string $email, string $displayName, string $passwordHash): User
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (email, display_name, password_hash)
             VALUES (?, ?, ?)
             RETURNING *',
        );
        $stmt->execute([$email, $displayName, $passwordHash]);

        return User::fromRow($stmt->fetch());
    }
}
