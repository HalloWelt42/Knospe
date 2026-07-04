<?php

declare(strict_types=1);

/*
 * Routentabelle: eine einfache, deklarative Liste [Methode, Pfad, Handler].
 * Kein Magie, kein verstecktes Auto-Routing - hier steht, was es gibt.
 *
 * Handler-Form:
 *   - Klassenname, der RequestHandlerInterface implementiert
 *   - oder "Klasse@methode"
 *
 * Lern mehr: docs/02-architektur/09-restful-apis-kurz.md
 */

use Knospe\Http\Handler\HealthHandler;

return [
    ['GET', '/api/health', HealthHandler::class],
];
