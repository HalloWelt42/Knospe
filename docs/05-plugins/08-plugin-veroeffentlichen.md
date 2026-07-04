# Plugin weitergeben

Ein Plugin ist ein in sich geschlossener Ordner - das macht das Teilen einfach. Wer es erhält, legt es unter `plugins/` ab und aktiviert es in der Ansicht **Plugins**. Damit das reibungslos klappt, gehören ein paar Dinge dazu.

## README im Plugin

Jedes Plugin bekommt eine eigene [`README.md`](../../README.md) (wie [`plugins/knospe-comments/README.md`](../../plugins/knospe-comments/README.md)). Sie beantwortet kurz: Was tut das Plugin? Welche Routen bringt es mit? Welche Hooks nutzt es? Welche Einstellungen gibt es? Ein Nutzer soll ohne Blick in den Code verstehen, was ihn erwartet.

## Versionierung

Die Version steht an zwei Stellen konsistent: im `manifest.json` und in `metadata()`. Erhöhe sie bei jeder Änderung - eine Fehlerbehebung als Patch (`1.0.0` zu `1.0.1`), eine neue Fähigkeit als Feature (`1.0.0` zu `1.1.0`). So erkennt jeder, ob eine neuere Fassung vorliegt.

## Sicherheit

Ein Plugin läuft mit denselben Rechten wie der Kern - Nachlässigkeit trifft die ganze Anwendung. Zwei Regeln sind Pflicht:

**Eingaben prüfen.** Nichts blind übernehmen. Der Kommentar-Controller wirft eine `ValidationException`, wenn Pflichtfelder fehlen:

```php
if ($postId <= 0 || $content === '') {
    throw new ValidationException('Eingabe ungültig', [
        'content' => 'Bitte Beitrag und Text angeben.',
    ]);
}
```

Ebenso prüfen, ob der Aufrufer überhaupt darf (`$this->auth->require()`) und ob er der Eigentümer ist, bevor etwas gelöscht wird.

**Vorbereitete Anweisungen.** Niemals Werte in SQL-Strings kleben. Immer Platzhalter und `execute()`:

```php
$stmt = $this->pdo->prepare('DELETE FROM comments WHERE id = ?');
$stmt->execute([$id]);
```

Das schützt vor SQL-Injection - der einzige verlässliche Weg.

## Als ZIP verpacken

Zum Weitergeben den Plugin-Ordner samt `manifest.json`, `src/`, `migrations/`, `frontend/` und [`README.md`](../../README.md) als ZIP packen - ohne temporäre Dateien oder lokale IDE-Ordner:

```bash
cd plugins && zip -r knospe-comments.zip knospe-comments
```

Der Empfänger entpackt nach `plugins/`, startet die Anwendung neu (Backend erkennt das Plugin, Frontend braucht einen Rebuild) und aktiviert es in der UI. Getestet sollte es vorher sein, siehe [Plugins testen](07-plugin-testing.md).
