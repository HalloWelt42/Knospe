<?php

declare(strict_types=1);

namespace Knospe\Plugin;

use Knospe\Core\Exception\NotFoundException;
use Knospe\Plugin\Hook\HookDispatcher;
use PDO;
use Throwable;

/**
 * Anwendungslogik rund um Plugins: auflisten, aktivieren, deaktivieren und
 * (fuer den Kernel) die aktivierten Plugins booten.
 *
 * Beim Aktivieren laufen die Plugin-Migrationen und enable() wird einmal
 * aufgerufen. Beim Deaktivieren bleiben die Daten erhalten (nur disable()).
 *
 * Lern mehr: docs/05-plugins/05-plugin-datenzugriff.md
 */
final class PluginService
{
    public function __construct(
        private PDO $pdo,
        private PluginLoader $loader,
    ) {
    }

    /**
     * Liste aller gefundenen Plugins mit ihrem Aktivierungsstatus.
     *
     * @return list<array{id: string, name: string, version: string, capabilities: list<string>, enabled: bool}>
     */
    public function available(): array
    {
        $enabled = $this->enabledMap();

        return array_map(
            static fn (array $m): array => [
                'id' => $m['id'],
                'name' => $m['name'],
                'version' => $m['version'],
                'capabilities' => $m['capabilities'],
                'enabled' => $enabled[$m['id']] ?? false,
            ],
            $this->loader->discover(),
        );
    }

    public function enable(string $id): void
    {
        $manifest = $this->loader->manifest($id)
            ?? throw new NotFoundException("Plugin {$id} nicht gefunden.");

        $this->upsert($manifest, true);
        $this->runMigrations($manifest);

        $plugin = $this->loader->instantiate($manifest);
        $plugin->enable($this->makeManager());
    }

    public function disable(string $id): void
    {
        $manifest = $this->loader->manifest($id)
            ?? throw new NotFoundException("Plugin {$id} nicht gefunden.");

        $this->pdo->prepare('UPDATE plugins SET enabled = false WHERE id = ?')->execute([$id]);

        try {
            $plugin = $this->loader->instantiate($manifest);
            $plugin->disable($this->makeManager());
        } catch (Throwable) {
            // Deaktivieren soll auch gelingen, wenn der Plugin-Code streikt.
        }
    }

    /**
     * Bootet alle aktivierten Plugins (registriert ihre Routen und Hooks).
     * Wird vom Kernel bei jeder Anfrage aufgerufen.
     */
    public function bootEnabled(PluginManager $manager): void
    {
        $enabled = $this->enabledMap();

        foreach ($this->loader->discover() as $manifest) {
            if (($enabled[$manifest['id']] ?? false) !== true) {
                continue;
            }

            try {
                $this->loader->instantiate($manifest)->boot($manager);
            } catch (Throwable) {
                // Ein fehlerhaftes Plugin darf die ganze App nicht lahmlegen.
            }
        }
    }

    /**
     * @return array<string, bool>
     */
    private function enabledMap(): array
    {
        try {
            $rows = $this->pdo->query('SELECT id, enabled FROM plugins')->fetchAll();
        } catch (Throwable) {
            return [];
        }

        $map = [];
        foreach ($rows as $row) {
            $map[(string) $row['id']] = (bool) $row['enabled'];
        }

        return $map;
    }

    /**
     * @param array{id: string, name: string, version: string} $manifest
     */
    private function upsert(array $manifest, bool $enabled): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO plugins (id, name, version, enabled)
             VALUES (?, ?, ?, ?)
             ON CONFLICT (id) DO UPDATE SET name = EXCLUDED.name, version = EXCLUDED.version, enabled = EXCLUDED.enabled',
        );
        $stmt->execute([$manifest['id'], $manifest['name'], $manifest['version'], $enabled ? 1 : 0]);
    }

    /**
     * Fuehrt die noch nicht angewendeten SQL-Migrationen eines Plugins aus.
     *
     * @param array{id: string, path: string} $manifest
     */
    private function runMigrations(array $manifest): void
    {
        $dir = $manifest['path'] . '/migrations';
        $files = glob($dir . '/*.sql') ?: [];
        sort($files);

        foreach ($files as $file) {
            if (preg_match('/^(\d+)_(.+)\.sql$/', basename($file), $m) !== 1) {
                continue;
            }
            $version = (int) $m[1];

            if ($this->migrationApplied($manifest['id'], $version)) {
                continue;
            }

            $up = $this->upPart((string) file_get_contents($file));

            $this->pdo->beginTransaction();
            try {
                $this->pdo->exec($up);
                $this->pdo->prepare(
                    'INSERT INTO plugin_migrations (plugin_id, version, name) VALUES (?, ?, ?)',
                )->execute([$manifest['id'], $version, $m[2]]);
                $this->pdo->commit();
            } catch (Throwable $e) {
                $this->pdo->rollBack();

                throw $e;
            }
        }
    }

    private function migrationApplied(string $pluginId, int $version): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM plugin_migrations WHERE plugin_id = ? AND version = ?',
        );
        $stmt->execute([$pluginId, $version]);

        return $stmt->fetchColumn() !== false;
    }

    private function upPart(string $content): string
    {
        $parts = preg_split('/^\s*--\s*DOWN:\s*$/mi', $content, 2);

        return trim($parts[0] ?? $content);
    }

    private function makeManager(): PluginManager
    {
        return new PluginManager(
            new HookDispatcher(),
            new RouteRegistry(),
            $this->pdo,
            $this->loader->path(),
        );
    }
}
