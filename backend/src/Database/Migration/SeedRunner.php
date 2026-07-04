<?php

declare(strict_types=1);

namespace Knospe\Database\Migration;

use PDO;
use RuntimeException;
use Throwable;

/**
 * Spielt Startdaten (Seeds) ein. Seeds sind nummerierte SQL-Dateien und
 * sollten idempotent sein (z.B. mit ON CONFLICT DO NOTHING), damit ein
 * erneutes Ausfuehren keine Duplikate erzeugt.
 *
 * Lern mehr: docs/03-datenbank/05-erste-tabelle-anlegen.md
 */
final class SeedRunner
{
    public function __construct(
        private PDO $pdo,
        private string $path,
    ) {
    }

    public function run(): void
    {
        $files = glob($this->path . '/*.sql') ?: [];
        sort($files);

        if ($files === []) {
            echo "Keine Seed-Dateien gefunden.\n";

            return;
        }

        foreach ($files as $file) {
            try {
                $this->pdo->exec((string) file_get_contents($file));
                echo '  + ' . basename($file) . "\n";
            } catch (Throwable $e) {
                throw new RuntimeException(
                    'Seed ' . basename($file) . ' fehlgeschlagen: ' . $e->getMessage(),
                    0,
                    $e,
                );
            }
        }

        echo "Fertig.\n";
    }
}
