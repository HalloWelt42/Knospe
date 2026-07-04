<?php

declare(strict_types=1);

namespace Knospe\Plugin;

use RuntimeException;

/**
 * Findet Plugins im Dateisystem und lädt ihren Code.
 *
 *  - discover(): liest alle plugins/<id>/manifest.json
 *  - registerAutoload(): meldet einen Autoloader an, der den Namensraum des
 *    Plugins auf seinen src/-Ordner abbildet (so bleibt jedes Plugin
 *    eigenständig, ohne Eintrag in der composer.json).
 *  - instantiate(): erzeugt die Boot-Klasse des Plugins.
 *
 * Lern mehr: ./docs/05-plugins/02-plugin-struktur.md
 */
final class PluginLoader
{
    public function __construct(private string $pluginsPath)
    {
    }

    public function path(): string
    {
        return $this->pluginsPath;
    }

    /**
     * @return list<array{id: string, name: string, version: string, namespace: string, bootClass: string, capabilities: list<string>, path: string}>
     */
    public function discover(): array
    {
        $result = [];

        foreach (glob($this->pluginsPath . '/*/manifest.json') ?: [] as $file) {
            $data = json_decode((string) file_get_contents($file), true);
            if (!is_array($data) || empty($data['id'])) {
                continue;
            }

            $result[] = [
                'id' => (string) $data['id'],
                'name' => (string) ($data['name'] ?? $data['id']),
                'version' => (string) ($data['version'] ?? '0.0.0'),
                'namespace' => (string) ($data['namespace'] ?? ''),
                'bootClass' => (string) ($data['bootClass'] ?? ''),
                'capabilities' => array_map('strval', (array) ($data['capabilities'] ?? [])),
                'path' => dirname($file),
            ];
        }

        return $result;
    }

    /**
     * @return array{id: string, name: string, version: string, namespace: string, bootClass: string, capabilities: list<string>, path: string}|null
     */
    public function manifest(string $id): ?array
    {
        foreach ($this->discover() as $manifest) {
            if ($manifest['id'] === $id) {
                return $manifest;
            }
        }

        return null;
    }

    /**
     * @param array{namespace: string, path: string} $manifest
     */
    public function registerAutoload(array $manifest): void
    {
        $namespace = $manifest['namespace'];
        $src = $manifest['path'] . '/src';

        if ($namespace === '') {
            return;
        }

        spl_autoload_register(static function (string $class) use ($namespace, $src): void {
            if (!str_starts_with($class, $namespace)) {
                return;
            }
            $relative = str_replace('\\', '/', substr($class, strlen($namespace)));
            $file = $src . '/' . $relative . '.php';
            if (is_file($file)) {
                require $file;
            }
        });
    }

    /**
     * @param array{namespace: string, bootClass: string, path: string} $manifest
     */
    public function instantiate(array $manifest): PluginInterface
    {
        $this->registerAutoload($manifest);

        $class = $manifest['bootClass'];
        if ($class === '' || !class_exists($class)) {
            throw new RuntimeException("Boot-Klasse nicht gefunden: {$class}");
        }

        $plugin = new $class();
        if (!$plugin instanceof PluginInterface) {
            throw new RuntimeException("Boot-Klasse {$class} implementiert PluginInterface nicht.");
        }

        return $plugin;
    }
}
