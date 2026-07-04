<?php

declare(strict_types=1);

namespace Knospe\Plugin;

/**
 * Bequeme Basis für Plugins: leere Standard-Umsetzungen. Ein Plugin
 * überschreibt nur, was es braucht (meist boot()), und liefert metadata().
 *
 * Lern mehr: ../../../docs/05-plugins/04-dein-erstes-plugin.md
 */
abstract class AbstractPlugin implements PluginInterface
{
    public function boot(PluginManager $manager): void
    {
    }

    public function enable(PluginManager $manager): void
    {
    }

    public function disable(PluginManager $manager): void
    {
    }
}
