<?php

declare(strict_types=1);

namespace Knospe\Core;

use Knospe\Http\Middleware\ErrorHandlingMiddleware;
use Knospe\Support\Config;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Der Kernel haelt alles zusammen: Er richtet den Container mit den
 * Grunddiensten ein und verarbeitet eine Anfrage, indem er den Router als
 * Kern-Handler hinter die Middleware-Pipeline haengt.
 *
 * Bewusst schlank - die eigentliche Arbeit steckt in Container, Router und
 * Pipeline, die jeweils fuer sich verstaendlich sind.
 *
 * Lern mehr: docs/02-architektur/02-ordner-bedeutung-detailliert.md
 */
final class Kernel
{
    private Container $container;

    public function __construct(private Config $config)
    {
        $this->container = new Container();
        $this->registerServices();
    }

    public function container(): Container
    {
        return $this->container;
    }

    private function registerServices(): void
    {
        // Konfiguration ist bereits fertig - als Instanz hinterlegen.
        $this->container->instance(Config::class, $this->config);

        // Ein einfacher Logger, der nach stderr schreibt (im Container sichtbar
        // ueber "./knospe logs php").
        $this->container->set(LoggerInterface::class, static function (): LoggerInterface {
            $logger = new Logger('knospe');
            $logger->pushHandler(new StreamHandler('php://stderr'));

            return $logger;
        });
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Router als Kern-Handler; er kennt den Container, um Handler zu holen.
        $router = new Router($this->container);

        /** @var iterable<array{0: string, 1: string, 2: string}> $routes */
        $routes = require dirname(__DIR__, 2) . '/config/routes.php';
        $router->addMany($routes);

        // Pipeline: erst Fehlerbehandlung, dann der Router. Weitere Middleware
        // (z.B. Auth) kommen in spaeteren Meilensteinen dazu.
        $pipeline = new Pipeline($router, [
            new ErrorHandlingMiddleware(
                $this->container->get(LoggerInterface::class),
                $this->config->appDebug,
            ),
        ]);

        return $pipeline->handle($request);
    }
}
