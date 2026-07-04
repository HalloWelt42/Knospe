# Aufbau eines Plugins

Ein Plugin ist ein Ordner unter `plugins/` mit einem festen, kleinen Grundriss. Am Beispiel `plugins/knospe-comments`:

```
knospe-comments/
  manifest.json          Steckbrief des Plugins
  src/                   PHP-Klassen (Boot-Klasse, Controller ...)
  migrations/            SQL-Dateien für eigene Tabellen
  frontend/              *.plugin.ts + Svelte-Komponenten
  README.md              Beschreibung für Menschen
```

## manifest.json

Der Steckbrief. So sieht er aus:

```json
{
  "id": "knospe-comments",
  "name": "Kommentare",
  "version": "1.0.0",
  "php": ">=8.5",
  "namespace": "KnospeComments\\",
  "bootClass": "KnospeComments\\CommentsPlugin",
  "capabilities": ["routes", "migrations", "hooks", "frontend"]
}
```

Die Felder: `id` ist eindeutig (auch der Ordnername und der Schlüssel in der Tabelle `plugins`). `namespace` ist der PSR-4-Präfix - alle Klassen in `src/` liegen darunter. `bootClass` nennt die Klasse, die der Kern beim Booten lädt. `capabilities` beschreibt, was das Plugin nutzt (routes, migrations, hooks, frontend) - nützlich für Anzeige und Verständnis.

## src/ mit Boot-Klasse

Die Boot-Klasse erweitert `Knospe\Plugin\AbstractPlugin`. Sie liefert `metadata()` und überschreibt genau die Lebenszyklus-Methoden, die sie braucht:

```php
final class CommentsPlugin extends AbstractPlugin
{
    public function boot(PluginManager $manager): void
    {
        $manager->routes()->add('GET', '/api/comments', CommentController::class . '@index');
    }
}
```

`boot()` läuft bei jeder Anfrage für aktivierte Plugins (Routen/Hooks anmelden). `enable()` läuft einmalig beim Aktivieren (Standardwerte setzen), `disable()` beim Deaktivieren - dabei niemals Daten löschen. Der `namespace` aus dem Manifest wird als Autoloader auf `src/` verdrahtet, sodass weitere Klassen wie der Controller einfach dazugelegt werden können.

## migrations/ und frontend/

`migrations/*.sql` legt eigene Tabellen an; die Dateien laufen beim Aktivieren, siehe [Plugin mit eigener Tabelle](05-plugin-datenzugriff.md). Unter `frontend/` registriert `comments.plugin.ts` eine Svelte-Komponente für einen Slot. Details zur Boot-Klasse und zum Registrieren stehen in [Dein erstes Plugin](04-dein-erstes-plugin.md).
