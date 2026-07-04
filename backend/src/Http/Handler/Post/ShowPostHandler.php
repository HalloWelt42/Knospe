<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Post;

use Knospe\Domain\Post\PostService;
use Knospe\Http\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Einzelnen Beitrag anzeigen. Die {id} kommt als Request-Attribut vom Router.
 */
final class ShowPostHandler implements RequestHandlerInterface
{
    public function __construct(private PostService $posts)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');

        return Json::response(['post' => $this->posts->get($id)->toArray()]);
    }
}
