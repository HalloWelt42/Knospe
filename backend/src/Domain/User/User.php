<?php

declare(strict_types=1);

namespace Knospe\Domain\User;

/**
 * Ein Nutzer als reine Datenklasse (Entity/DTO).
 *
 * readonly: einmal erzeugt, nie mehr verändert - das macht das Objekt
 * berechenbar. Der Passwort-Hash bleibt im Objekt, wird aber von toArray()
 * bewusst NICHT ausgegeben (er darf nie zum Client gelangen).
 *
 * Lern mehr: ./docs/01-grundlagen/04-properties-constructor-promotion.md
 */
final readonly class User
{
    public function __construct(
        public int $id,
        public string $email,
        public string $displayName,
        public string $passwordHash,
        public string $createdAt,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            email: (string) $row['email'],
            displayName: (string) $row['display_name'],
            passwordHash: (string) $row['password_hash'],
            createdAt: (string) $row['created_at'],
        );
    }

    /**
     * Öffentliche Darstellung - ohne Passwort-Hash.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'displayName' => $this->displayName,
            'createdAt' => $this->createdAt,
        ];
    }
}
