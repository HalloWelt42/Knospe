<?php

declare(strict_types=1);

namespace Knospe\Support;

use Knospe\Core\Exception\UnauthorizedException;
use Knospe\Domain\User\User;
use Knospe\Domain\User\UserRepositoryInterface;

/**
 * Kennt den aktuell angemeldeten Nutzer (aus der Sitzung) und kapselt
 * An- und Abmelden. Handler fragen hier nach "wer ist das?" statt selbst in
 * der Sitzung zu wühlen.
 *
 * Lern mehr: ./docs/08-debugging-und-sicherheit/07-sicherheits-basics.md
 */
final class AuthContext
{
    public function __construct(
        private Session $session,
        private UserRepositoryInterface $users,
    ) {
    }

    public function userId(): ?int
    {
        $value = $this->session->get('user_id');

        return $value === null ? null : (int) $value;
    }

    public function user(): ?User
    {
        $id = $this->userId();

        return $id === null ? null : $this->users->findById($id);
    }

    /**
     * Liefert den angemeldeten Nutzer oder wirft 401.
     */
    public function require(): User
    {
        $user = $this->user();

        if ($user === null) {
            throw new UnauthorizedException();
        }

        return $user;
    }

    public function login(User $user): void
    {
        // Sitzungs-ID nach der Anmeldung erneuern (Schutz vor Session-Fixation).
        $this->session->regenerate();
        $this->session->set('user_id', $user->id);
    }

    public function logout(): void
    {
        $this->session->destroy();
    }
}
