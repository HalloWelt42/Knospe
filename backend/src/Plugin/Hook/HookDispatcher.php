<?php

declare(strict_types=1);

namespace Knospe\Plugin\Hook;

/**
 * Der Hook-Bus. Zwei Arten von Einhaengepunkten:
 *
 *  - Aktionen (doAction): "etwas ist passiert" - alle registrierten Rueckrufe
 *    werden aufgerufen (z.B. post.created).
 *  - Filter (applyFilter): "veraendere diesen Wert" - der Wert wird durch alle
 *    Rueckrufe gereicht und veraendert zurueckgegeben (z.B. comment.content).
 *
 * Kleinere Prioritaetszahl = frueher dran.
 *
 * Lern mehr: docs/05-plugins/03-hooks-und-events.md
 */
final class HookDispatcher
{
    /** @var array<string, list<array{priority: int, cb: callable}>> */
    private array $actions = [];

    /** @var array<string, list<array{priority: int, cb: callable}>> */
    private array $filters = [];

    public function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        $this->actions[$hook][] = ['priority' => $priority, 'cb' => $callback];
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        $this->filters[$hook][] = ['priority' => $priority, 'cb' => $callback];
    }

    public function doAction(string $hook, mixed ...$args): void
    {
        foreach ($this->sorted($this->actions[$hook] ?? []) as $callback) {
            $callback(...$args);
        }
    }

    public function applyFilter(string $hook, mixed $value, mixed ...$args): mixed
    {
        foreach ($this->sorted($this->filters[$hook] ?? []) as $callback) {
            $value = $callback($value, ...$args);
        }

        return $value;
    }

    /**
     * @param list<array{priority: int, cb: callable}> $entries
     * @return list<callable>
     */
    private function sorted(array $entries): array
    {
        usort($entries, static fn (array $a, array $b): int => $a['priority'] <=> $b['priority']);

        return array_map(static fn (array $e): callable => $e['cb'], $entries);
    }
}
