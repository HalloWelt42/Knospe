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
 * Aktiviert ein Plugin (führt dessen Migrationen aus). Erfordert Anmeldung.
 */
final class PluginEnableHandler implements RequestHandlerInterface
{
    public function __construct(
        private PluginService $plugins,
        private AuthContext $auth,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->auth->require();
        $id = (string) $request->getAttribute('id');
        $this->plugins->enable($id);

        return Json::response(['status' => 'aktiviert', 'id' => $id]);
    }
}
