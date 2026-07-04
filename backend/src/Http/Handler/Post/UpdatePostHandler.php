<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Post;

use Knospe\Domain\Post\PostService;
use Knospe\Http\Input;
use Knospe\Http\Json;
use Knospe\Support\AuthContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Beitrag ändern. Erfordert Anmeldung; nur der Autor darf ändern
 * (die Rechteprüfung passiert im PostService).
 */
final class UpdatePostHandler implements RequestHandlerInterface
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
        $data = Input::json($request);

        $post = $this->posts->update(
            $id,
            $user->id,
            (string) ($data['title'] ?? ''),
            (string) ($data['body'] ?? ''),
        );

        return Json::response(['post' => $post->toArray()]);
    }
}
