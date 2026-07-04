<?php

declare(strict_types=1);

namespace Knospe\Plugin;

/**
 * Der Vertrag, den jedes Plugin erfuellt.
 *
 * Lebenszyklus:
 *  - boot():   bei jeder Anfrage fuer aktivierte Plugins - hier Routes, Hooks
 *              und Filter registrieren.
 *  - enable(): einmalig beim Aktivieren - z.B. Standard-Einstellungen setzen.
 *  - disable(): beim Deaktivieren - aufraeumen, aber KEINE Daten loeschen.
 *
 * Fuer den Alltag gibt es AbstractPlugin mit leeren Standard-Umsetzungen,
 * sodass ein Plugin nur ueberschreibt, was es wirklich braucht.
 *
 * Lern mehr: docs/05-plugins/02-plugin-struktur.md
 */
interface PluginInterface
{
    public function metadata(): PluginMetadata;

    public function boot(PluginManager $manager): void;

    public function enable(PluginManager $manager): void;

    public function disable(PluginManager $manager): void;
}
