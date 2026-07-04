<?php

declare(strict_types=1);

namespace Knospe\Tests\Unit;

use Knospe\Core\Exception\MethodNotAllowedException;
use Knospe\Core\Exception\NotFoundException;
use Knospe\Core\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Testet das Routing: Treffer mit Platzhalter, 404 und 405.
 * Lern mehr: docs/02-architektur/03-request-flow-diagramm.md
 */
final class RouterTest extends TestCase
{
    private function container(): ContainerInterface
    {
        // Ein winziger Container, der immer denselben Handler liefert. Der
        // Handler gibt den Pfad-Parameter "id" als Antwort-Text zurück.
        return new class () implements ContainerInterface {
            public function get(string $id): mixed
            {
                return new class () implements RequestHandlerInterface {
                    public function handle(ServerRequestInterface $request): ResponseInterface
                    {
                        return new Response(200, [], (string) $request->getAttribute('id'));
                    }
                };
            }

            public function has(string $id): bool
            {
                return true;
            }
        };
    }

    private function request(string $method, string $path): ServerRequestInterface
    {
        return (new Psr17Factory())->createServerRequest($method, $path);
    }

    public function testMatchExtractsParameter(): void
    {
        $router = new Router($this->container());
        $router->add('GET', '/api/posts/{id}', 'AnyHandler');

        $response = $router->handle($this->request('GET', '/api/posts/42'));

        self::assertSame('42', (string) $response->getBody());
    }

    public function testUnknownRouteThrowsNotFound(): void
    {
        $router = new Router($this->container());
        $router->add('GET', '/api/posts', 'AnyHandler');

        $this->expectException(NotFoundException::class);
        $router->handle($this->request('GET', '/api/gibtsnicht'));
    }

    public function testWrongMethodThrowsMethodNotAllowed(): void
    {
        $router = new Router($this->container());
        $router->add('GET', '/api/posts', 'AnyHandler');

        $this->expectException(MethodNotAllowedException::class);
        $router->handle($this->request('POST', '/api/posts'));
    }
}
