# Constructor Promotion und readonly

Fast jede Klasse muss im Konstruktor ihre Werte entgegennehmen und in Eigenschaften ablegen. Früher war das viel Tipparbeit mit dreifacher Wiederholung. Modernes PHP kürzt das ab. Das WARUM: Weniger Rumpfcode bedeutet weniger Stellen, an denen sich Fehler verstecken, und man sieht sofort, welche Daten ein Objekt ausmacht.

## Das alte Muster

```php
final class Post
{
    private int $id;
    private string $title;

    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }
}
```

Der Name jeder Eigenschaft steht dreimal da: in der Deklaration, im Parameter und in der Zuweisung.

## Constructor Promotion

Seit PHP 8 schreibst du die Sichtbarkeit direkt vor den Parameter. PHP legt die Eigenschaft dann automatisch an und weist sie zu. Aus drei Zeilen wird eine.

```php
final class Post
{
    public function __construct(
        public int $id,
        public string $title,
    ) {}
}
```

## readonly

`readonly` markiert eine Eigenschaft als schreibgeschützt: Sie darf genau einmal - nämlich im Konstruktor - gesetzt werden und ist danach unveränderlich. Der Vorteil: Ein Objekt, dessen Werte sich nie heimlich ändern, ist leichter zu verstehen und sicherer im Umlauf.

In Knospe sind die Entitäten deshalb als `readonly class` deklariert. Das macht jede einzelne Eigenschaft automatisch schreibgeschützt:

```php
final readonly class Post
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public int $authorId,
    ) {}
}
```

So sieh es in [`backend/src/Domain/Post/Post.php`](../../backend/src/Domain/Post/Post.php) und [`backend/src/Domain/User/User.php`](../../backend/src/Domain/User/User.php). Willst du einen geänderten Beitrag, erzeugst du bewusst ein neues Objekt, statt das alte zu überschreiben.

Die Grundidee von Konstruktor und Eigenschaften steht in [Klassen und Objekte](01-oop-klassen-intro.md). Wie sich einzelne Eigenschaften mit Logik anreichern lassen, zeigt [Property Hooks (PHP 8.4)](05-property-hooks.md).
