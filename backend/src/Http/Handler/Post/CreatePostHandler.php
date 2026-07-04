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
 * Neuen Beitrag anlegen. Erfordert eine Anmeldung.
 */
final class CreatePostHandler implements RequestHandlerInterface
{
    public function __construct(
        private PostService $posts,
        private AuthContext $auth,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->auth->require();
        $data = Input::json($request);

        $post = $this->posts->create(
            $user->id,
            (string) ($data['title'] ?? ''),
            (string) ($data['body'] ?? ''),
        );

        return Json::response(['post' => $post->toArray()], 201);
    }
}
