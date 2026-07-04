<?php

declare(strict_types=1);

namespace Knospe\Support;

/**
 * Dünne Hülle um die nativen PHP-Sitzungen.
 *
 * Warum überhaupt eine Klasse? Damit der übrige Code nicht direkt mit
 * $_SESSION und session_*() arbeitet, sondern mit klaren Methoden - das ist
 * leichter zu lesen und später austauschbar.
 *
 * Die Anmeldung nutzt eine Sitzung mit HttpOnly-Cookie; zusätzlich schützt
 * ein CSRF-Token vor ungewollten Anfragen.
 *
 * Lern mehr: docs/08-debugging-und-sicherheit/07-sicherheits-basics.md
 */
final class Session
{
    public function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
            ]);
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $this->start();

        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    public function remove(string $key): void
    {
        $this->start();
        unset($_SESSION[$key]);
    }

    public function regenerate(): void
    {
        $this->start();
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        $this->start();
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Liefert das CSRF-Token der Sitzung und erzeugt es beim ersten Mal.
     */
    public function csrfToken(): string
    {
        $this->start();

        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return (string) $_SESSION['csrf'];
    }
}
