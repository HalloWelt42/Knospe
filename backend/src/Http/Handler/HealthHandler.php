<?php

declare(strict_types=1);

namespace Knospe\Http\Handler;

use Knospe\Database\Connection;
use Knospe\Http\Json;
use Knospe\Support\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * Gesundheitscheck: meldet Anwendung, PHP-Version und ob die Datenbank
 * erreichbar ist. Nuetzlich fuer den ersten Verbindungstest und fuer
 * Betriebs-Ueberwachung.
 *
 * Die Datenbankverbindung wird bewusst hier (und nicht im Konstruktor)
 * aufgebaut, damit ein Ausfall als "nicht verbunden" gemeldet werden kann,
 * statt den ganzen Handler scheitern zu lassen.
 *
 * Lern mehr: docs/02-architektur/03-request-flow-diagramm.md
 */
final class HealthHandler implements RequestHandlerInterface
{
    public function __construct(private Config $config)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $datenbank = ['verbunden' => false, 'fehler' => null];

        try {
            $pdo = Connection::create($this->config);
            $pdo->query('SELECT 1');
            $datenbank['verbunden'] = true;
        } catch (Throwable $e) {
            $datenbank['fehler'] = $e->getMessage();
        }

        return Json::response([
            'status' => 'ok',
            'anwendung' => 'Knospe',
            'php' => PHP_VERSION,
            'zeit' => date('c'),
            'datenbank' => $datenbank,
        ]);
    }
}
