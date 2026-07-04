# Namensregeln

Ein guter Name erklärt sich selbst. Wer `PostRepositoryInterface` liest, weiss sofort, worum es geht - ohne in die Datei zu schauen. Deshalb lohnt es sich, bei Namen sorgfältig zu sein. In Knospe gelten dafür feste Regeln, damit sich der ganze Code gleich anfühlt.

## Gross- und Kleinschreibung

- Klassen, Interfaces und Enums in PascalCase: `PostService`, `PluginManager`, `HttpException`.
- Methoden und Variablen in camelCase: `findAll()`, `$authContext`, `$currentUser`.
- Konstanten in UPPER_SNAKE_CASE: `const DEFAULT_LIMIT = 20;`.

Diese drei Formen reichen für fast alles. Sie stammen aus dem PSR-12-Umfeld und sind in `backend/src` konsequent umgesetzt.

## Booleans sprechen lassen

Wahrheitswerte beginnen mit `is`, `has` oder `can`. So liest sich eine Bedingung wie ein Satz:

```php
if ($plugin->isEnabled() && $user->hasRole('admin')) {
    // ...
}
```

`isEnabled()` ist besser als `enabled()` oder `getStatus()`, weil der Name schon verrät, dass die Antwort ja oder nein lautet.

## Keine Abkürzungen

Schreib Namen aus. `$repository` statt `$repo`, `$configuration` statt `$cfg`, `PasswordHasher` statt `PwdHash`. Abkürzungen sparen ein paar Zeichen und kosten später Zeit beim Verstehen. Etablierte Kürzel wie `id`, `url` oder `http` sind in Ordnung, weil sie jeder kennt.

## Sprache: Code Englisch, Prosa Deutsch

Alle Bezeichner im Code sind Englisch und ASCII: `deletePost`, nicht `beitragLoeschen`. Das hält den Code kompatibel mit den Bibliotheken und Standards. Kommentare und Dokumentation dagegen sind Deutsch mit echten Umlauten - ae, oe, ue sind hier nicht erlaubt. Warum das so getrennt ist, zeigt [Docblocks und Kommentare](03-docblocks-und-comments.md). Den passenden Formatierungsrahmen liefert [Codestil (PSR-12)](01-code-style-psr-12.md).
