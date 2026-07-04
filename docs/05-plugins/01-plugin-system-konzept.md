# Das Plugin-System

Ein Boilerplate soll man erweitern, ohne den Kern anzufassen. Genau dafür gibt es das Plugin-System: neue Endpunkte, neue Tabellen, neue Bildschirmbereiche - alles in einem eigenen Ordner unter `plugins/`, den man kopieren, teilen oder wieder löschen kann. Der Kern bleibt schlank und stabil, die Funktionen wachsen aussen herum.

## Die drei Bausteine

**Discovery und Registry.** Beim Start liest der Kern jeden Plugin-Ordner ein und trägt ihn in die Tabelle `plugins` ein (bekannt, aktiviert ja/nein). Für jede Anfrage läuft `boot()` nur bei den aktivierten Plugins. Dort meldet ein Plugin seine Routen bei der `RouteRegistry` an, die der Kernel danach in die Routentabelle des Routers übernimmt. So kommt `GET /api/comments` in die Anwendung, ohne dass eine Kern-Datei geändert wird.

**Hooks.** Ueber den `HookDispatcher` reagiert ein Plugin auf Ereignisse (Aktionen) oder verbiegt Werte (Filter). Der Kern wirft z.B. `post.created` und `post.deleted`; ein Plugin hängt sich an. Mehr dazu in [Hooks: Aktionen und Filter](03-hooks-und-events.md).

**Datenzugriff.** Der `PluginManager` reicht die PDO-Verbindung, Einstellungen (`getOption`/`setOption`) und Pfade durch. Eigene Tabellen legt ein Plugin per Migration an, siehe [Plugin mit eigener Tabelle](05-plugin-datenzugriff.md).

## Zwei Welten: Backend zur Laufzeit, Frontend zur Bauzeit

Wichtig zu verstehen: Das PHP-Backend lädt Plugins **zur Laufzeit** - Aktivieren wirkt sofort, ohne Neubau. Das Svelte-Frontend dagegen wird von Vite **zur Bauzeit** zusammengestellt. Eine Datei `*.plugin.ts` wird über `import.meta.glob` eingesammelt und in einen Slot gehängt (siehe `PluginSlot`). Neue Frontend-Teile erfordern deshalb einen Rebuild.

## Das Beispiel-Plugin

`plugins/knospe-comments` zeigt alle Fähigkeiten auf einmal: eigene Routen, eine Migration (Tabelle `comments`), eine Aktion auf `post.deleted`, einen Filter `comment.content` und einen Frontend-Teil unter dem Beitrag. Es ist die beste Vorlage - kopieren und loslegen, siehe [Dein erstes Plugin](04-dein-erstes-plugin.md).
