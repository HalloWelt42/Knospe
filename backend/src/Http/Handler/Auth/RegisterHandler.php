<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Auth;

use Knospe\Domain\User\UserService;
use Knospe\Http\Input;
use Knospe\Http\Json;
use Knospe\Support\AuthContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Registrierung: legt einen Nutzer an und meldet ihn direkt an.
 * Lern mehr: ../../../../../docs/08-debugging-und-sicherheit/08-passwort-sicherheit.md
 */
final class RegisterHandler implements RequestHandlerInterface
{
    public function __construct(
        private UserService $users,
        private AuthContext $auth,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = Input::json($request);

        $user = $this->users->register(
            (string) ($data['email'] ?? ''),
            (string) ($data['displayName'] ?? ''),
            (string) ($data['password'] ?? ''),
        );

        $this->auth->login($user);

        return Json::response(['user' => $user->toArray()], 201);
    }
}
