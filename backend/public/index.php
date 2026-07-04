<?php

declare(strict_types=1);

/*
 * Knospe - Front-Controller (Platzhalter im Meilenstein M0)
 * ---------------------------------------------------------
 * Dies ist der einzige Einstiegspunkt fuer alle HTTP-Anfragen. Aktuell
 * beantwortet er nur einen Gesundheitscheck und prueft die Datenbank.
 * Ab Meilenstein M1 uebernimmt hier der richtige Kern: Autoloader,
 * Konfiguration, DI-Container, Router und die Middleware-Pipeline.
 *
 * Lern mehr: docs/02-architektur/03-request-flow-diagramm.md
 */

// Beim eingebauten PHP-Server existierende Dateien direkt ausliefern.
if (PHP_SAPI === 'cli-server') {
    $requested = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $file = __DIR__ . $requested;
    if ($requested !== '/' && is_file($file)) {
        return false;
    }
}

header('Content-Type: application/json; charset=utf-8');

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($path === '/api/health') {
    $datenbank = ['verbunden' => false, 'fehler' => null];

    try {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            getenv('DB_HOST') ?: 'postgres',
            getenv('DB_PORT') ?: '5432',
            getenv('DB_NAME') ?: 'knospe',
        );
        $pdo = new PDO(
            $dsn,
            getenv('DB_USER') ?: 'knospe',
            getenv('DB_PASSWORD') ?: '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3],
        );
        $pdo->query('SELECT 1');
        $datenbank['verbunden'] = true;
    } catch (Throwable $e) {
        // Im Gesundheitscheck ist die Meldung hilfreich; im echten Kern
        // werden interne Fehler spaeter nicht mehr nach aussen gegeben.
        $datenbank['fehler'] = $e->getMessage();
    }

    echo json_encode([
        'status' => 'ok',
        'anwendung' => 'Knospe',
        'php' => PHP_VERSION,
        'zeit' => date('c'),
        'datenbank' => $datenbank,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return;
}

http_response_code(404);
echo json_encode(
    ['status' => 'fehler', 'meldung' => 'Nicht gefunden', 'pfad' => $path],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
);
