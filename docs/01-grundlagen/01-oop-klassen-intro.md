# Klassen und Objekte

Eine Klasse ist eine Schablone. Sie beschreibt, welche Daten ein Ding hat und was es kann - aber sie ist noch nicht das Ding selbst. Ein konkretes Ding, das nach dieser Schablone gebaut wird, heißt Objekt (oder Instanz). Das WARUM dahinter: Statt einzelne Variablen wild durch den Code zu reichen, bündeln wir zusammengehörige Daten und ihr Verhalten an einer Stelle. Das macht den Code lesbarer und änderbar.

In Knospe findest du das an vielen Stellen. Ein Beitrag wird durch die Klasse `Post` in [`backend/src/Domain/Post/Post.php`](../../backend/src/Domain/Post/Post.php) beschrieben, ein Nutzer durch `User` in [`backend/src/Domain/User/User.php`](../../backend/src/Domain/User/User.php).

## Eigenschaften und Methoden

Zwei Bausteine bilden eine Klasse:

- Eigenschaften (Properties) sind die Daten, zum Beispiel Titel und Inhalt eines Beitrags.
- Methoden sind Funktionen, die zur Klasse gehören und mit diesen Daten arbeiten.

## Sichtbarkeit

Jede Eigenschaft und Methode hat eine Sichtbarkeit. `public` ist von außen zugreifbar, `private` nur innerhalb der Klasse selbst. Der Sinn: Wir kapseln interne Details, damit anderer Code sich nicht darauf verlässt.

## Konstruktor

Der Konstruktor `__construct()` wird automatisch beim Erzeugen eines Objekts aufgerufen und setzt die Startwerte.

```php
final class Post
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
    ) {}
}

$post = new Post(1, 'Hallo', 'Mein erster Beitrag');
echo $post->title; // Hallo
```

Das Schlüsselwort `new` baut aus der Schablone ein echtes Objekt. Mit `->` greifst du auf dessen Eigenschaften zu.

Wie die Werte im Konstruktor besonders kompakt geschrieben werden, zeigt [Constructor Promotion und readonly](04-properties-constructor-promotion.md). Wie eine Klasse Verhalten von einer anderen erbt, erklärt [Vererbung](02-oop-vererbung.md).
