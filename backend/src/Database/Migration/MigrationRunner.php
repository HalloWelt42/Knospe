<?php

declare(strict_types=1);

namespace Knospe\Database\Migration;

use PDO;
use RuntimeException;
use Throwable;

/**
 * Ein kleiner, selbstgeschriebener Migrationsrunner.
 *
 * Prinzip:
 *  - Migrationen sind nummerierte SQL-Dateien (001_..., 002_..., ...).
 *  - Jede Datei hat einen UP-Teil und optional einen DOWN-Teil, getrennt
 *    durch die Zeile "-- DOWN:".
 *  - Die Tabelle schema_migrations merkt sich, was schon gelaufen ist.
 *  - PostgreSQL kann Schema-Änderungen in Transaktionen zurücknehmen,
 *    darum läuft jede Migration in einer eigenen Transaktion.
 *
 * Keine Magie, kein ORM - alles ist als SQL sichtbar und nachvollziehbar.
 * Lern mehr: ../../../../docs/03-datenbank/04-migrationen-system.md
 */
final class MigrationRunner
{
    public function __construct(
        private PDO $pdo,
        private string $path,
    ) {
        $this->ensureTrackingTable();
    }

    private function ensureTrackingTable(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS schema_migrations (
                version INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                executed_at TIMESTAMPTZ NOT NULL DEFAULT now()
            )',
        );
    }

    /**
     * Führt alle noch nicht ausgeführten Migrationen aus.
     */
    public function migrate(): void
    {
        $pending = $this->pending();

        if ($pending === []) {
            echo "Keine ausstehenden Migrationen.\n";

            return;
        }

        foreach ($pending as $migration) {
            $this->pdo->beginTransaction();

            try {
                $this->pdo->exec($this->upPart($migration['content']));
                $this->pdo
                    ->prepare('INSERT INTO schema_migrations (version, name) VALUES (?, ?)')
                    ->execute([$migration['version'], $migration['name']]);
                $this->pdo->commit();
                echo "  + {$migration['file']}\n";
            } catch (Throwable $e) {
                $this->pdo->rollBack();

                throw new RuntimeException(
                    "Migration {$migration['file']} fehlgeschlagen: " . $e->getMessage(),
                    0,
                    $e,
                );
            }
        }

        echo "Fertig.\n";
    }

    /**
     * Nimmt die letzten $steps Migrationen zurück (DOWN-Teil).
     */
    public function rollback(int $steps = 1): void
    {
        $executed = array_reverse($this->executedVersions());
        $todo = array_slice($executed, 0, max(1, $steps));

        if ($todo === []) {
            echo "Nichts zum Zurücknehmen.\n";

            return;
        }

        foreach ($todo as $version) {
            $file = $this->fileForVersion($version);
            if ($file === null) {
                echo "  ! Keine Datei für Version {$version}\n";
                continue;
            }

            $down = $this->downPart((string) file_get_contents($file));
            if ($down === null || trim($down) === '') {
                echo '  ! Kein DOWN-Teil in ' . basename($file) . "\n";
                continue;
            }

            $this->pdo->beginTransaction();

            try {
                $this->pdo->exec($down);
                $this->pdo
                    ->prepare('DELETE FROM schema_migrations WHERE version = ?')
                    ->execute([$version]);
                $this->pdo->commit();
                echo '  - ' . basename($file) . "\n";
            } catch (Throwable $e) {
                $this->pdo->rollBack();

                throw new RuntimeException('Rollback fehlgeschlagen: ' . $e->getMessage(), 0, $e);
            }
        }
    }

    /**
     * Zeigt, welche Migrationen ausgeführt sind und welche fehlen.
     */
    public function status(): void
    {
        $executed = $this->executedVersions();

        echo "Migrationen:\n";
        foreach ($this->all() as $migration) {
            $mark = in_array($migration['version'], $executed, true) ? '[x]' : '[ ]';
            echo "  {$mark} {$migration['file']}\n";
        }
    }

    /**
     * @return list<array{version: int, name: string, file: string, content: string}>
     */
    private function all(): array
    {
        $files = glob($this->path . '/*.sql') ?: [];
        $migrations = [];

        foreach ($files as $file) {
            $base = basename($file);
            if (preg_match('/^(\d+)_(.+)\.sql$/', $base, $m) !== 1) {
                continue;
            }

            $migrations[] = [
                'version' => (int) $m[1],
                'name' => $m[2],
                'file' => $base,
                'content' => (string) file_get_contents($file),
            ];
        }

        usort($migrations, static fn (array $a, array $b): int => $a['version'] <=> $b['version']);

        return $migrations;
    }

    /**
     * @return list<array{version: int, name: string, file: string, content: string}>
     */
    private function pending(): array
    {
        $executed = $this->executedVersions();

        return array_values(array_filter(
            $this->all(),
            static fn (array $m): bool => !in_array($m['version'], $executed, true),
        ));
    }

    /**
     * @return list<int>
     */
    private function executedVersions(): array
    {
        $rows = $this->pdo
            ->query('SELECT version FROM schema_migrations ORDER BY version ASC')
            ->fetchAll(PDO::FETCH_COLUMN);

        return array_map('intval', $rows);
    }

    private function fileForVersion(int $version): ?string
    {
        foreach ($this->all() as $migration) {
            if ($migration['version'] === $version) {
                return $this->path . '/' . $migration['file'];
            }
        }

        return null;
    }

    private function upPart(string $content): string
    {
        $parts = preg_split('/^\s*--\s*DOWN:\s*$/mi', $content, 2);

        return trim($parts[0] ?? $content);
    }

    private function downPart(string $content): ?string
    {
        $parts = preg_split('/^\s*--\s*DOWN:\s*$/mi', $content, 2);

        return isset($parts[1]) ? trim($parts[1]) : null;
    }
}
