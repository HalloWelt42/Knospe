# Plugin-Einstellungen

Nicht jede Kleinigkeit braucht eine eigene Tabelle. Für einzelne Schalter und Werte gibt es einen fertigen Schlüssel-Wert-Speicher: die Tabelle `plugin_options`. Ein Plugin liest und schreibt sie über den `PluginManager`.

## getOption und setOption

Beide Methoden nehmen als erstes Argument die Plugin-ID, damit sich zwei Plugins nie in die Quere kommen:

```php
// Wert setzen
$manager->setOption('knospe-comments', 'moderation', true);

// Wert lesen (mit Standardwert, falls noch nichts gespeichert)
$moderation = $manager->getOption('knospe-comments', 'moderation', false);
```

Die Werte werden als JSON abgelegt, deshalb funktionieren nicht nur Zeichenketten, sondern auch `bool`, Zahlen oder ganze Listen. Die Tabelle dahinter:

```sql
CREATE TABLE plugin_options (
    plugin_id    VARCHAR(64)  NOT NULL,
    option_key   VARCHAR(120) NOT NULL,
    option_value TEXT,
    PRIMARY KEY (plugin_id, option_key)
);
```

## Standardwerte in enable() setzen

Der richtige Ort für Standardwerte ist `enable()` - das läuft einmalig beim Aktivieren. Genau das macht das Kommentar-Plugin:

```php
public function enable(PluginManager $manager): void
{
    $manager->setOption('knospe-comments', 'moderation', false);
}
```

## Beispiel: Moderation

Die Einstellung `moderation` lesen wir dort aus, wo ein Kommentar entsteht, und entscheiden danach über den Ablauf:

```php
public function boot(PluginManager $manager): void
{
    $moderation = $manager->getOption('knospe-comments', 'moderation', false);

    $manager->hooks()->addFilter('comment.content', static function (string $text) use ($moderation): string {
        $text = trim($text);
        return $moderation ? '[wartet auf Freigabe] ' . $text : $text;
    });
}
```

Bei `moderation = true` wird jeder Kommentar markiert, bei `false` bleibt er unverändert. Weil `setOption` per `ON CONFLICT` aktualisiert, überschreibt ein erneutes Setzen den alten Wert sauber - so kann später eine Einstellungsseite im Frontend denselben Schlüssel umschalten.

Größere Datenmengen gehören dagegen in eine eigene Tabelle, siehe [Plugin mit eigener Tabelle](05-plugin-datenzugriff.md).
