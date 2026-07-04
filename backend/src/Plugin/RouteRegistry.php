<?php

declare(strict_types=1);

namespace Knospe\Plugin;

/**
 * Sammelt Routen, die Plugins beisteuern. Der Kernel liest sie nach dem
 * Booten der Plugins aus und ergänzt damit die Routentabelle des Routers.
 *
 * Lern mehr: ../../../docs/05-plugins/01-plugin-system-konzept.md
 */
final class RouteRegistry
{
    /** @var list<array{0: string, 1: string, 2: string}> */
    private array $routes = [];

    public function add(string $method, string $path, string $handler): void
    {
        $this->routes[] = [$method, $path, $handler];
    }

    /**
     * @return list<array{0: string, 1: string, 2: string}>
     */
    public function all(): array
    {
        return $this->routes;
    }
}
