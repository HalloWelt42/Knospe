# Docblocks und Kommentare

Kommentare sind kein Zeichen von schlechtem Code - sie sind ein Werkzeug. Aber sie werden oft falsch benutzt. Die Faustregel in Knospe: Der Code sagt, WAS passiert, der Kommentar sagt, WARUM. Ein Kommentar, der nur den Code wiederholt, ist überflüssig und veraltet schnell.

## Was ein Kommentar erklären soll

Schlecht, weil offensichtlich:

```php
// erhoehe zaehler um eins
$count++;
```

Gut, weil es eine Entscheidung erklärt:

```php
// Argon2id statt schnellerer Verfahren, damit Brute-Force teuer bleibt.
$hash = $this->hasher->hash($password);
```

Der zweite Kommentar spart einer neuen Person Nachfragen. Genau dafür sind Kommentare da.

## Docblocks für die Typen, die PHP nicht kennt

Moderne Signaturen tragen die Typen bereits, deshalb sind Docblocks nur dort nötig, wo die Signatur nicht genau genug ist - zum Beispiel bei Arrays oder bei Ausnahmen. Dann helfen `@param`, `@return` und `@throws`:

```php
/**
 * @return list<Post> alle Beiträge, neueste zuerst
 * @throws NotFoundException wenn die Tabelle fehlt
 */
public function findAll(): array
{
    // ...
}
```

`@param` beschreibt einen Parameter genauer (etwa `@param array<string, mixed> $data`), `@return` den Rückgabewert, `@throws` die Ausnahmen, mit denen Aufrufer rechnen müssen. Die Ausnahmeklassen liegen in `backend/src/Core/Exception`.

## Lern-mehr-Verweise

Wenn ein Stück Code eng an einem Konzept hängt, hilft ein Verweis in die Dokumentation. So findet die nächste Person schnell den Hintergrund:

```php
// Ablauf des Plugin-Lebenszyklus: siehe docs/07-plugins/
```

Solche Verweise verbinden Code und Doku. Zur Formatierung der Docblocks selbst siehe [Codestil (PSR-12)](01-code-style-psr-12.md), zur Benennung [Namensregeln](02-naming-konventionen.md).
