<?php

declare(strict_types=1);

namespace Knospe\Tests;

use Knospe\Database\Connection;
use Knospe\Support\Config;
use PDO;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Basis für Integrationstests. Jeder Test läuft in einer Transaktion, die in
 * tearDown() zurückgerollt wird - so bleibt die Datenbank sauber, ganz ohne
 * Aufräum-SQL. Es wird nur die projekteigene Datenbank angefasst.
 *
 * Lern mehr: docs/09-entwicklung/04-testing-kultur.md
 */
abstract class TestCase extends BaseTestCase
{
    protected PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = Connection::create(Config::fromEnv());
        $this->pdo->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }
}
