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
use Knospe\Plugin\Hook\HookDispatcher;
use Knospe\Plugin\PluginLoader;
use Knospe\Plugin\PluginManager;
use Knospe\Plugin\PluginService;
use Knospe\Plugin\RouteRegistry;
use Knospe\Support\Config;
use Knospe\Support\Session;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Der Kernel hält alles zusammen: Container einrichten, aktivierte Plugins
 * booten (die dabei Routen und Hooks registrieren) und eine Anfrage durch die
 * Middleware-Pipeline zum Router leiten.
 *
 * Lern mehr: ./docs/02-architektur/02-ordner-bedeutung-detailliert.md
 */
final class Kernel
{
    private Container $container;
    private string $pluginsPath;

    public function __construct(private Config $config)
    {
        $this->pluginsPath = dirname(__DIR__, 3) . '/plugins';
        $this->container = new Container();
        $this->registerServices();
    }

    public function container(): Container
    {
        return $this->container;
    }

    private function registerServices(): void
    {
        $this->container->instance(Config::class, $this->config);

        $this->container->set(LoggerInterface::class, static function (): LoggerInterface {
            $logger = new Logger('knospe');
            $logger->pushHandler(new StreamHandler('php://stderr'));

            return $logger;
        });

        // Datenbank: lazy verbunden.
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

        // Hook-Bus: eine gemeinsame Instanz für Plugins UND Kern-Dienste.
        $this->container->instance(HookDispatcher::class, new HookDispatcher());

        // Plugin-Werkzeuge.
        $this->container->instance(PluginLoader::class, new PluginLoader($this->pluginsPath));
        $this->container->set(
            PluginService::class,
            static fn (Container $c): PluginService
                => new PluginService($c->get(PDO::class), $c->get(PluginLoader::class)),
        );
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $router = new Router($this->container);

        /** @var iterable<array{0: string, 1: string, 2: string}> $coreRoutes */
        $coreRoutes = require dirname(__DIR__, 2) . '/config/routes.php';
        $router->addMany($coreRoutes);

        // Aktivierte Plugins booten - sie registrieren Routen und Hooks.
        // Fällt die Datenbank aus, läuft die App ohne Plugins weiter.
        try {
            $routeRegistry = new RouteRegistry();
            $manager = new PluginManager(
                $this->container->get(HookDispatcher::class),
                $routeRegistry,
                $this->container->get(PDO::class),
                $this->pluginsPath,
            );
            $this->container->get(PluginService::class)->bootEnabled($manager);
            $router->addMany($routeRegistry->all());
        } catch (Throwable) {
            // Ohne Plugins weiter.
        }

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
