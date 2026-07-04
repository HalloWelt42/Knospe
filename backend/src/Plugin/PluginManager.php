<?php

declare(strict_types=1);

namespace Knospe\Plugin;

use Knospe\Plugin\Hook\HookDispatcher;
use PDO;

/**
 * Die zentrale Anlaufstelle, die ein Plugin beim boot()/enable() bekommt.
 * Ueber sie registriert es Hooks und Routen, liest/schreibt Einstellungen
 * und greift auf die Datenbank zu.
 *
 * Lern mehr: ./docs/05-plugins/02-plugin-struktur.md
 */
final class PluginManager
{
    public function __construct(
        private HookDispatcher $hooks,
        private RouteRegistry $routes,
        private PDO $pdo,
        private string $pluginsPath,
    ) {
    }

    public function hooks(): HookDispatcher
    {
        return $this->hooks;
    }

    public function routes(): RouteRegistry
    {
        return $this->routes;
    }

    public function db(): PDO
    {
        return $this->pdo;
    }

    public function pluginPath(string $id): string
    {
        return $this->pluginsPath . '/' . $id;
    }

    public function getOption(string $pluginId, string $key, mixed $default = null): mixed
    {
        $stmt = $this->pdo->prepare(
            'SELECT option_value FROM plugin_options WHERE plugin_id = ? AND option_key = ?',
        );
        $stmt->execute([$pluginId, $key]);
        $value = $stmt->fetchColumn();

        return $value === false ? $default : json_decode((string) $value, true);
    }

    public function setOption(string $pluginId, string $key, mixed $value): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO plugin_options (plugin_id, option_key, option_value)
             VALUES (?, ?, ?)
             ON CONFLICT (plugin_id, option_key)
             DO UPDATE SET option_value = EXCLUDED.option_value',
        );
        $stmt->execute([$pluginId, $key, json_encode($value)]);
    }
}
