<?php

declare(strict_types=1);

namespace Knospe\Core;

use Knospe\Database\Connection;
use Knospe\Domain\Post\PostgresPostRepository;
use Knospe\Domain\Post\PostRepositoryInterface;
use Knospe\Domain\User\PostgresUserRepository;
use Knospe\Domain\User\UserRepositoryInterface;
use Knospe\Http\Middleware\CsrfMiddleware;
use Knospe\Http\Middleware\ErrorHandlingMiddleware;
use Knospe\Support\Config;
use Knospe\Support\Session;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Der Kernel haelt alles zusammen: Er richtet den Container mit den
 * Grunddiensten ein und verarbeitet eine Anfrage, indem er den Router als
 * Kern-Handler hinter die Middleware-Pipeline haengt.
 *
 * Die konkreten Datenbank-Umsetzungen werden hier an ihre Interfaces
 * gebunden - der uebrige Code kennt nur die Interfaces.
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
        // Fertige Werte als Instanz hinterlegen.
        $this->container->instance(Config::class, $this->config);

        // Logger nach stderr (sichtbar ueber "./knospe logs php").
        $this->container->set(LoggerInterface::class, static function (): LoggerInterface {
            $logger = new Logger('knospe');
            $logger->pushHandler(new StreamHandler('php://stderr'));

            return $logger;
        });

        // Datenbank: lazy, erst beim ersten Bedarf verbunden.
        $this->container->set(PDO::class, fn (): PDO => Connection::create($this->config));

        // Repository-Interfaces an ihre PostgreSQL-Umsetzungen binden.
        $this->container->set(
            UserRepositoryInterface::class,
            static fn (Container $c): UserRepositoryInterface
                => new PostgresUserRepository($c->get(PDO::class)),
        );
        $this->container->set(
            PostRepositoryInterface::class,
            static fn (Container $c): PostRepositoryInterface
                => new PostgresPostRepository($c->get(PDO::class)),
        );
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $router = new Router($this->container);

        /** @var iterable<array{0: string, 1: string, 2: string}> $routes */
        $routes = require dirname(__DIR__, 2) . '/config/routes.php';
        $router->addMany($routes);

        // Pipeline: erst Fehlerbehandlung (aussen), dann CSRF-Schutz, dann Router.
        $pipeline = new Pipeline($router, [
            new ErrorHandlingMiddleware(
                $this->container->get(LoggerInterface::class),
                $this->config->appDebug,
            ),
            new CsrfMiddleware($this->container->get(Session::class)),
        ]);

        return $pipeline->handle($request);
    }
}
