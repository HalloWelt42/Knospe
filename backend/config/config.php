<?php

declare(strict_types=1);

/*
 * Baut das zentrale Konfigurationsobjekt aus den Umgebungsvariablen.
 * Diese Datei wird vom Front-Controller eingebunden.
 * Lern mehr: docs/02-architektur/10-konfiguration-und-umgebung.md
 */

use Knospe\Support\Config;

return Config::fromEnv();
