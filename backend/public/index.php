<?php

declare(strict_types=1);

/*
 * Knospe - Front-Controller
 * -------------------------
 * Der einzige Einstiegspunkt für alle HTTP-Anfragen. Der Ablauf ist
 * bewusst offen sichtbar:
 *   1. Statische Dateien (beim eingebauten Server) direkt ausliefern
 *   2. Composer-Autoloader laden
 *   3. .env laden (für Bare-Metal; im Container kommen Werte aus Compose)
 *   4. Konfiguration bauen
 *   5. Kernel erzeugen
 *   6. Anfrage aus den PHP-Globals erzeugen (PSR-7)
 *   7. Anfrage vom Kernel verarbeiten lassen
 *   8. Antwort senden
 *
 * Lern mehr: ../../docs/02-architektur/03-request-flow-diagramm.md
 */

// 1. Beim eingebauten PHP-Server existierende Dateien direkt ausliefern.
if (PHP_SAPI === 'cli-server') {
    $requested = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    if ($requested !== '/' && is_file(__DIR__ . $requested)) {
        return false;
    }
}

// 2. Autoloader.
require dirname(__DIR__) . '/vendor/autoload.php';

// 3. .env laden, falls vorhanden (überschreibt keine bereits gesetzten Werte).
if (is_file(dirname(__DIR__, 2) . '/.env')) {
    Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2))->safeLoad();
}

// 4. Konfiguration.
$config = require dirname(__DIR__) . '/config/config.php';

// 5. Kernel.
$kernel = new Knospe\Core\Kernel($config);

// 6. Anfrage aus den Globals (PSR-7).
$factory = new Nyholm\Psr7\Factory\Psr17Factory();
$creator = new Nyholm\Psr7Server\ServerRequestCreator($factory, $factory, $factory, $factory);
$request = $creator->fromGlobals();

// 7. Verarbeiten.
$response = $kernel->handle($request);

// 8. Antwort senden.
http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header($name . ': ' . $value, false);
    }
}
echo $response->getBody();
