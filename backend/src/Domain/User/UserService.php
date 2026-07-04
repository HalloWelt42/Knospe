<?php

declare(strict_types=1);

namespace Knospe\Domain\User;

use Knospe\Core\Exception\UnauthorizedException;
use Knospe\Core\Exception\ValidationException;
use Knospe\Support\PasswordHasher;

/**
 * Geschäftslogik rund um Nutzer: Registrierung und Anmeldung.
 * Der Handler ruft nur diese Methoden auf und kümmert sich nicht um Details.
 *
 * Lern mehr: ../../../../docs/02-architektur/05-services-pattern.md
 */
final class UserService
{
    // Ein ungültiger Hash, gegen den auch bei unbekanntem Nutzer geprüft wird,
    // damit die Antwortzeit keinen Rückschluss zulässt (verify liefert false).
    private const DUMMY_HASH = '$argon2id$v=19$m=65536,t=4,p=1$MDAwMDAwMDAwMDAw$0000000000000000000000000000000000000000000';

    public function __construct(private UserRepositoryInterface $users)
    {
    }

    public function register(string $email, string $displayName, string $password): User
    {
        $email = trim($email);
        $displayName = trim($displayName);

        $errors = [];
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Bitte eine gültige E-Mail-Adresse angeben.';
        }
        if ($displayName === '') {
            $errors['displayName'] = 'Bitte einen Anzeigenamen angeben.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Das Passwort muss mindestens 8 Zeichen haben.';
        }
        if ($errors !== []) {
            throw new ValidationException('Eingabe ungültig', $errors);
        }

        if ($this->users->findByEmail($email) !== null) {
            throw new ValidationException('Eingabe ungültig', [
                'email' => 'Diese E-Mail-Adresse ist bereits vergeben.',
            ]);
        }

        return $this->users->create($email, $displayName, PasswordHasher::hash($password));
    }

    public function authenticate(string $email, string $password): User
    {
        $user = $this->users->findByEmail(trim($email));

        // Auch bei unbekanntem Nutzer verifizieren (Schutz vor Timing-Angriffen).
        $hash = $user !== null ? $user->passwordHash : self::DUMMY_HASH;

        $ok = PasswordHasher::verify($password, $hash);

        if ($user === null || !$ok) {
            throw new UnauthorizedException('E-Mail oder Passwort ist falsch.');
        }

        return $user;
    }
}
