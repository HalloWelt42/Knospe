<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Post;

use Knospe\Domain\Post\Post;
use Knospe\Domain\Post\PostService;
use Knospe\Http\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Liste der neuesten Beiträge. Öffentlich lesbar.
 * Lern mehr: docs/02-architektur/09-restful-apis-kurz.md
 */
final class ListPostsHandler implements RequestHandlerInterface
{
    public function __construct(private PostService $posts)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return Json::response([
            'posts' => array_map(static fn (Post $p): array => $p->toArray(), $this->posts->list()),
        ]);
    }
}
