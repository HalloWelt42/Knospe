# Plugin: Kommentare (knospe-comments)

Ein vollständiges Beispiel-Plugin für Knospe. Es fügt Beiträgen Kommentare hinzu und zeigt dabei alle Bausteine, die ein Plugin nutzen kann.

## Was es demonstriert

- **Eigene Routen**: `GET/POST /api/comments`, `DELETE /api/comments/{id}`
- **Eigene Migration**: Tabelle `comments` (läuft beim Aktivieren)
- **Aktion (Hook)**: reagiert auf `post.deleted` und räumt Kommentare weg
- **Filter (Hook)**: `comment.content` bereinigt den Text
- **Frontend-Teil**: `CommentsPanel.svelte` erscheint unter jedem Beitrag

## Aufbau

```
knospe-comments/
├── manifest.json                 # Metadaten (id, namespace, bootClass, ...)
├── src/
│   ├── CommentsPlugin.php         # boot()/enable(): Routen, Hooks, Optionen
│   └── CommentController.php       # die Endpunkte
├── migrations/
│   └── 001_create_comments_table.sql
├── frontend/
│   ├── comments.plugin.ts          # meldet die Komponente an einen Slot
│   └── CommentsPanel.svelte        # die Oberfläche
└── README.md
```

## Aktivieren

In der App unter **Plugins** auf "Aktivieren" klicken. Dabei laufen die Migrationen automatisch. Danach erscheinen die Kommentare in der Beitragsansicht.

## Eigenes Plugin daraus machen

Diesen Ordner kopieren, in `manifest.json` `id`, `namespace` und `bootClass` anpassen, die Klassen im neuen Namensraum umbenennen - fertig ist das Gerüst.

Mehr dazu im Handbuch: `docs/05-plugins/`.
