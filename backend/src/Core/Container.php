<?php

declare(strict_types=1);

namespace Knospe\Core;

use Closure;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;

/**
 * Ein kleiner Dependency-Injection-Container (PSR-11).
 *
 * Aufgaben:
 *  - Dienste über Fabriken registrieren (set)
 *  - fertige Objekte hinterlegen (instance)
 *  - Abhängigkeiten automatisch verkabeln (autowire per Reflection)
 *  - jeden Dienst genau einmal bauen (Singleton pro Container)
 *
 * Bewusst klein gehalten: kein magisches Framework, sondern nachvollziehbar.
 * Lern mehr: docs/02-architektur/04-dependency-injection.md
 */
final class Container implements ContainerInterface
{
    /** @var array<string, Closure> */
    private array $factories = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    /**
     * Registriert eine Fabrik. Sie wird erst beim ersten get() ausgeführt.
     */
    public function set(string $id, Closure $factory): void
    {
        $this->factories[$id] = $factory;
        unset($this->instances[$id]);
    }

    /**
     * Hinterlegt ein bereits fertiges Objekt (oder einen Wert).
     */
    public function instance(string $id, mixed $value): void
    {
        $this->instances[$id] = $value;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->instances)
            || isset($this->factories[$id])
            || class_exists($id);
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (isset($this->factories[$id])) {
            return $this->instances[$id] = ($this->factories[$id])($this);
        }

        if (class_exists($id)) {
            return $this->instances[$id] = $this->autowire($id);
        }

        throw new ContainerException("Dienst nicht gefunden: {$id}");
    }

    /**
     * Baut eine Klasse und löst ihre Konstruktor-Abhängigkeiten selbst auf.
     * Typ-Abhängigkeiten werden rekursiv aus dem Container geholt,
     * Standardwerte werden übernommen.
     */
    private function autowire(string $class): object
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $arguments = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $arguments[] = $this->get($type->getName());
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
                continue;
            }

            throw new ContainerException(
                "Kann Parameter \${$parameter->getName()} von {$class} nicht auflösen.",
            );
        }

        return $reflection->newInstanceArgs($arguments);
    }
}
