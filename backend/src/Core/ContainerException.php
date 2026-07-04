<?php

declare(strict_types=1);

namespace Knospe\Core;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

/**
 * Fehler des Containers, z.B. wenn ein Dienst nicht aufgelöst werden kann.
 * Erfüllt die PSR-11-Schnittstelle für "nicht gefunden".
 */
final class ContainerException extends RuntimeException implements NotFoundExceptionInterface
{
}
