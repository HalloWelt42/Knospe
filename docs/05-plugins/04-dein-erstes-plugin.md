# Dein erstes Plugin

Der schnellste Weg: `plugins/knospe-comments` kopieren, umbenennen, anpassen. Hier bauen wir es Schritt für Schritt nach - ein Plugin, das eine Begrüßung liefert.

## 1. Ordner und Manifest

Neuen Ordner `plugins/knospe-hallo/` anlegen mit `manifest.json`:

```json
{
  "id": "knospe-hallo",
  "name": "Hallo",
  "version": "1.0.0",
  "php": ">=8.5",
  "namespace": "KnospeHallo\\",
  "bootClass": "KnospeHallo\\HalloPlugin",
  "capabilities": ["routes", "hooks"]
}
```

## 2. Boot-Klasse

`src/HalloPlugin.php` erweitert `AbstractPlugin`:

```php
<?php

declare(strict_types=1);

namespace KnospeHallo;

use Knospe\Plugin\AbstractPlugin;
use Knospe\Plugin\PluginManager;
use Knospe\Plugin\PluginMetadata;

final class HalloPlugin extends AbstractPlugin
{
    public function metadata(): PluginMetadata
    {
        return new PluginMetadata(
            id: 'knospe-hallo',
            name: 'Hallo',
            version: '1.0.0',
            capabilities: ['routes', 'hooks'],
        );
    }

    public function boot(PluginManager $manager): void
    {
        // Route registrieren.
        $manager->routes()->add('GET', '/api/hallo', HalloController::class . '@index');

        // Hook: auf neu angelegte Beiträge reagieren.
        $manager->hooks()->addAction('post.created', static function ($post): void {
            error_log('Neuer Beitrag: ' . $post->id);
        });
    }
}
```

## 3. Controller

`src/HalloController.php`. Der Container verkabelt die Abhängigkeiten selbst:

```php
final class HalloController
{
    public function index(): ResponseInterface
    {
        return Json::response(['gruss' => 'Hallo aus dem Plugin']);
    }
}
```

## 4. In der UI aktivieren

Plugins werden nicht per Konfiguration scharf geschaltet, sondern in der App. Oberfläche öffnen, zur Ansicht **Plugins** wechseln (Komponente `PluginsView`), das neue Plugin erscheint und lässt sich per Klick aktivieren. Dahinter ruft der Aufruf `POST /api/plugins/{id}/enable` die einmalige `enable()`-Methode auf und lässt etwaige Migrationen laufen. Danach antwortet `GET /api/hallo`.

Mehr zu den Hooks in [Hooks: Aktionen und Filter](03-hooks-und-events.md), zur Struktur in [Aufbau eines Plugins](02-plugin-struktur.md).
