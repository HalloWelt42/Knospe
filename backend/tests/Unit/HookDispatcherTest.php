<?php

declare(strict_types=1);

namespace Knospe\Tests\Unit;

use Knospe\Plugin\Hook\HookDispatcher;
use PHPUnit\Framework\TestCase;

/**
 * Testet den Hook-Bus: Aktionen laufen nach Prioritaet, Filter veraendern Werte.
 * Lern mehr: docs/05-plugins/03-hooks-und-events.md
 */
final class HookDispatcherTest extends TestCase
{
    public function testActionsRunInPriorityOrder(): void
    {
        $dispatcher = new HookDispatcher();
        $reihenfolge = [];

        $dispatcher->addAction('x', static function () use (&$reihenfolge): void {
            $reihenfolge[] = 'spaeter';
        }, 20);
        $dispatcher->addAction('x', static function () use (&$reihenfolge): void {
            $reihenfolge[] = 'frueher';
        }, 10);

        $dispatcher->doAction('x');

        self::assertSame(['frueher', 'spaeter'], $reihenfolge);
    }

    public function testActionReceivesArguments(): void
    {
        $dispatcher = new HookDispatcher();
        $empfangen = null;

        $dispatcher->addAction('post.deleted', static function (int $id) use (&$empfangen): void {
            $empfangen = $id;
        });
        $dispatcher->doAction('post.deleted', 42);

        self::assertSame(42, $empfangen);
    }

    public function testFilterTransformsValueInOrder(): void
    {
        $dispatcher = new HookDispatcher();
        $dispatcher->addFilter('v', static fn (string $s): string => $s . '1', 10);
        $dispatcher->addFilter('v', static fn (string $s): string => $s . '2', 20);

        self::assertSame('start12', $dispatcher->applyFilter('v', 'start'));
    }

    public function testUnknownHookIsNoop(): void
    {
        $dispatcher = new HookDispatcher();
        $dispatcher->doAction('gibtsnicht');

        self::assertSame('wert', $dispatcher->applyFilter('gibtsnicht', 'wert'));
    }
}
