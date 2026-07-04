<?php

declare(strict_types=1);

namespace Knospe\Domain\User;

/**
 * Vertrag fuer den Nutzer-Datenzugriff. Der uebrige Code kennt nur dieses
 * Interface, nicht die konkrete Datenbank - so bleibt die Umsetzung
 * austauschbar und testbar.
 *
 * Lern mehr: docs/03-datenbank/09-repository-pattern.md
 */
interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function create(string $email, string $displayName, string $passwordHash): User;
}
