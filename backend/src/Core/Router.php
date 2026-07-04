<?php

declare(strict_types=1);

namespace Knospe\Core;

use Knospe\Core\Exception\MethodNotAllowedException;
use Knospe\Core\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * Ein einfacher Router. Er vergleicht Methode und Pfad einer Anfrage mit
 * einer deklarativen Routentabelle, holt den passenden Handler aus dem
 * Container und ruft ihn auf. Pfad-Platzhalter wie {id} werden als
 * Request-Attribute bereitgestellt.
 *
 * Der Router ist selbst ein RequestHandler (PSR-15) und bildet das Ende
 * der Middleware-Pipeline.
 *
 * Lern mehr: ../../../docs/02-architektur/03-request-flow-diagramm.md
 */
final class Router implements RequestHandlerInterface
{
    /** @var list<array{method: string, pattern: string, handler: string}> */
    private array $routes = [];

    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * Fügt eine Route hinzu. Der Handler ist entweder eine Klasse, die
     * RequestHandlerInterface implementiert, oder die Form "Klasse@methode".
     */
    public function add(string $method, string $path, string $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $path,
            'handler' => $handler,
        ];
    }

    /**
     * @param iterable<array{0: string, 1: string, 2: string}> $routes
     */
    public function addMany(iterable $routes): void
    {
        foreach ($routes as [$method, $path, $handler]) {
            $this->add($method, $path, $handler);
        }
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $method = $request->getMethod();
        $path = $this->normalize($request->getUri()->getPath());

        /** @var list<string> $allowed */
        $allowed = [];

        foreach ($this->routes as $route) {
            $params = $this->match($route['pattern'], $path);
            if ($params === null) {
                continue;
            }

            if ($route['method'] !== $method) {
                $allowed[] = $route['method'];
                continue;
            }

            foreach ($params as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }

            return $this->run($route['handler'], $request);
        }

        if ($allowed !== []) {
            throw new MethodNotAllowedException(
                'Methode nicht erlaubt: ' . $method . ' (erlaubt: ' . implode(', ', $allowed) . ')',
            );
        }

        throw new NotFoundException('Keine Route für ' . $method . ' ' . $path);
    }

    private function normalize(string $path): string
    {
        $trimmed = rtrim($path, '/');

        return $trimmed === '' ? '/' : $trimmed;
    }

    /**
     * Vergleicht ein Muster mit einem Pfad. Bei Treffer werden die
     * benannten Platzhalter als Map zurückgegeben, sonst null.
     *
     * @return array<string, string>|null
     */
    private function match(string $pattern, string $path): ?array
    {
        $normalized = $this->normalize($pattern);
        $regex = '#^' . preg_replace(
            '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
            '(?<$1>[^/]+)',
            $normalized,
        ) . '$#';

        if (preg_match($regex, $path, $matches) !== 1) {
            return null;
        }

        return array_filter(
            $matches,
            static fn (int|string $key): bool => is_string($key),
            ARRAY_FILTER_USE_KEY,
        );
    }

    private function run(string $handler, ServerRequestInterface $request): ResponseInterface
    {
        if (str_contains($handler, '@')) {
            [$class, $method] = explode('@', $handler, 2);
            $instance = $this->container->get($class);

            /** @var callable $callable */
            $callable = [$instance, $method];

            return $callable($request);
        }

        $instance = $this->container->get($handler);

        if ($instance instanceof RequestHandlerInterface) {
            return $instance->handle($request);
        }

        if (is_callable($instance)) {
            return $instance($request);
        }

        throw new RuntimeException("Handler {$handler} ist nicht aufrufbar.");
    }
}
