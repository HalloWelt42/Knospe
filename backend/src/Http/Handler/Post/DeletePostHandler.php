<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Post;

use Knospe\Domain\Post\PostService;
use Knospe\Http\Json;
use Knospe\Support\AuthContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Beitrag löschen. Erfordert Anmeldung; nur der Autor darf löschen.
 */
final class DeletePostHandler implements RequestHandlerInterface
{
    public function __construct(
        private PostService $posts,
        private AuthContext $auth,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->auth->require();
        $id = (int) $request->getAttribute('id');

        $this->posts->delete($id, $user->id);

        return Json::response(['status' => 'gelöscht']);
    }
}
