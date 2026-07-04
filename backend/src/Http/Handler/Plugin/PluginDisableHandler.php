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
 * Deaktiviert ein Plugin. Die Daten des Plugins bleiben erhalten.
 */
final class PluginDisableHandler implements RequestHandlerInterface
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
        $this->plugins->disable($id);

        return Json::response(['status' => 'deaktiviert', 'id' => $id]);
    }
}
