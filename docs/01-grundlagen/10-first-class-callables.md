# First-Class-Callables

Eine Funktion oder Methode als Wert weiterzugeben, war in PHP lange umständlich: mal ein String `'strlen'`, mal ein Array `[$objekt, 'methode']`. Die First-Class-Callable-Schreibweise `funktion(...)` macht daraus einen echten Verweis auf die Funktion selbst. Die drei Punkte sind wörtlich so zu tippen. Das WARUM: Der Verweis ist typsicher, der Editor findet die Zieldefinition, und Umbenennungen greifen mit.

## String versus fn(...)

Früher:

```php
$laengen = array_map('strlen', $woerter);
```

Der String `'strlen'` ist für den Editor nur Text. Tippfehler wie `'strln'` fallen erst zur Laufzeit auf, und "Gehe zur Definition" funktioniert nicht.

Neu:

```php
$laengen = array_map(strlen(...), $woerter);
```

`strlen(...)` ist ein echter Verweis. Das Werkzeug prüft, ob es die Funktion gibt, kennt ihre Signatur und springt hin.

## Nutzen bei array_map und array_filter

Besonders schön wird es mit eigenen Methoden. Angenommen, ein Repository liefert `Post`-Objekte (siehe `backend/src/Domain/Post/Post.php`), und wir wollen daraus die Titel ziehen:

```php
$titel = array_map(
    fn (Post $post): string => $post->title,
    $posts,
);
```

Oder mit einer Methode als Verweis - hier `Post::toArray` für die JSON-Ausgabe:

```php
$daten = array_map(Post::toArray(...), $posts);
```

Und beim Filtern:

```php
$sichtbare = array_filter($posts, $service->isVisible(...));
```

Statt einer Wegwerf-Funktion, die nur `$service->isVisible($post)` aufruft, steht direkt der Methodenverweis da. Kürzer und klarer.

## Bessere Werkzeugunterstützung

Der eigentliche Gewinn ist nicht Tipparbeit, sondern Sicherheit. Ein Verweis mit `(...)`:

- wird beim Kompilieren geprüft (die Funktion muss existieren),
- trägt die vollständige Signatur, sodass die statische Analyse Typen abgleicht,
- lässt sich per "Gehe zur Definition" verfolgen,
- wird bei Umbenennungen automatisch mitgezogen.

Das passt zur Knospe-Linie: kleine, fokussierte Funktionen, klare Namen, gut nachvollziehbar. Wo eine benannte Methode gemeint ist, sagt der Code das jetzt auch typsicher.

Weiter mit [Der Pipe-Operator](11-pipe-operator.md).
