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

use Knospe\Http\Handler\Auth\CsrfHandler;
use Knospe\Http\Handler\Auth\LoginHandler;
use Knospe\Http\Handler\Auth\LogoutHandler;
use Knospe\Http\Handler\Auth\MeHandler;
use Knospe\Http\Handler\Auth\RegisterHandler;
use Knospe\Http\Handler\HealthHandler;
use Knospe\Http\Handler\Post\CreatePostHandler;
use Knospe\Http\Handler\Post\DeletePostHandler;
use Knospe\Http\Handler\Post\ListPostsHandler;
use Knospe\Http\Handler\Post\ShowPostHandler;
use Knospe\Http\Handler\Post\UpdatePostHandler;

return [
    // Betrieb
    ['GET', '/api/health', HealthHandler::class],

    // Sitzung und Anmeldung
    ['GET', '/api/csrf', CsrfHandler::class],
    ['POST', '/api/auth/register', RegisterHandler::class],
    ['POST', '/api/auth/login', LoginHandler::class],
    ['POST', '/api/auth/logout', LogoutHandler::class],
    ['GET', '/api/auth/me', MeHandler::class],

    // Beitraege
    ['GET', '/api/posts', ListPostsHandler::class],
    ['GET', '/api/posts/{id}', ShowPostHandler::class],
    ['POST', '/api/posts', CreatePostHandler::class],
    ['PUT', '/api/posts/{id}', UpdatePostHandler::class],
    ['DELETE', '/api/posts/{id}', DeletePostHandler::class],
];
