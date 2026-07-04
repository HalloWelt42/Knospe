<?php

declare(strict_types=1);

namespace Knospe\Plugin;

/**
 * Metadaten eines Plugins, wie sie in der manifest.json stehen.
 * Lern mehr: ../../../docs/05-plugins/02-plugin-struktur.md
 */
final readonly class PluginMetadata
{
    /**
     * @param list<string> $capabilities
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $version,
        public array $capabilities = [],
    ) {
    }
}
