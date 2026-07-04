<?php

declare(strict_types=1);

namespace Knospe\Http\Handler\Plugin;

use Knospe\Http\Json;
use Knospe\Plugin\PluginService;
use Knospe\Support\AuthContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Liste aller gefundenen Plugins mit Aktivierungsstatus. Erfordert Anmeldung.
 * Lern mehr: ../../../../../docs/05-plugins/01-plugin-system-konzept.md
 */
final class PluginListHandler implements RequestHandlerInterface
{
    public function __construct(
        private PluginService $plugins,
        private AuthContext $auth,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->auth->require();

        return Json::response(['plugins' => $this->plugins->available()]);
    }
}
