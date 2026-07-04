<?php

declare(strict_types=1);

namespace Knospe\Database;

use Knospe\Support\Config;
use PDO;

/**
 * Baut die PDO-Verbindung zu PostgreSQL mit sicheren Voreinstellungen.
 *
 *  - ERRMODE_EXCEPTION: Fehler kommen als Exceptions, nicht still
 *  - FETCH_ASSOC:       Ergebniszeilen als assoziative Arrays
 *  - EMULATE_PREPARES=false: echte Prepared Statements (Schutz vor Injection)
 *
 * Lern mehr: ../../../docs/03-datenbank/03-pdo-und-sql.md
 */
final class Connection
{
    public static function create(Config $config): PDO
    {
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $config->dbHost,
            $config->dbPort,
            $config->dbName,
        );

        return new PDO($dsn, $config->dbUser, $config->dbPassword, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
