# Hooks: Aktionen und Filter

Hooks sind Einhängepunkte. Der Kern ruft sie an bestimmten Stellen auf, und ein Plugin hängt sich dort ein - ohne den Kern zu ändern. Es gibt zwei Arten, umgesetzt in [`backend/src/Plugin/Hook/HookDispatcher.php`](../../backend/src/Plugin/Hook/HookDispatcher.php).

## doAction: "etwas ist passiert"

Eine Aktion meldet ein Ereignis. Alle registrierten Rückrufe werden aufgerufen, ein Rückgabewert gibt es nicht. Der Kern wirft die Aktion so:

```php
$this->hooks->doAction('post.deleted', $id);
```

Ein Plugin reagiert darauf in seinem `boot()`:

```php
$manager->hooks()->addAction('post.deleted', static function (int $postId) use ($manager): void {
    $manager->db()->prepare('DELETE FROM comments WHERE post_id = ?')->execute([$postId]);
});
```

## applyFilter: "verändere diesen Wert"

Ein Filter reicht einen Wert durch alle Rückrufe und gibt ihn verändert zurück. So bereinigt das Kommentar-Plugin den Text:

```php
$manager->hooks()->addFilter('comment.content', static fn (string $text): string => trim($text));
```

Aufgerufen wird der Filter dort, wo der Wert gebraucht wird - im Controller:

```php
$content = $this->hooks->applyFilter('comment.content', (string) ($data['content'] ?? ''));
```

Merksatz: Aktion wirft man weg, Filter gibt man zurück.

## Priorität

`addAction` und `addFilter` nehmen als drittes Argument eine Priorität (Standard `10`). **Kleinere Zahl = früher dran.** Bei Filtern bestimmt die Reihenfolge, welches Plugin den Wert zuerst anfasst. So läuft eine Bereinigung vor einer späteren Prüfung:

```php
$manager->hooks()->addFilter('comment.content', $bereinigen, 5);
$manager->hooks()->addFilter('comment.content', $prüfen, 20);
```

## Verfügbare Kern-Hooks

- `post.created` (Aktion) - nach dem Anlegen, übergibt das Post-Objekt.
- `post.deleted` (Aktion) - nach dem Löschen, übergibt die `int`-ID.
- `comment.content` (Filter) - der Kommentartext, bevor er gespeichert wird.

Geworfen werden die Post-Aktionen in [`backend/src/Domain/Post/PostService.php`](../../backend/src/Domain/Post/PostService.php). Wie man einen Hook im eigenen Plugin registriert, zeigt [Dein erstes Plugin](04-dein-erstes-plugin.md).
