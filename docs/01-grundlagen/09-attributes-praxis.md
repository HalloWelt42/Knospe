# Attribute in der Praxis

Ein Attribut ist selbst nur eine ganz normale Klasse, die mit dem Attribut `#[Attribute]` markiert ist. Diese Markierung erlaubt PHP, sie in eckigen Klammern an anderen Code zu hängen. Das WARUM: So definierst du eine eigene, typgeprüfte Beschreibung, die dein Code später auswerten kann.

## Ein Attribut deklarieren

```php
#[Attribute(Attribute::TARGET_PROPERTY)]
final class MaxLength
{
    public function __construct(
        public int $max,
        public string $message = 'Wert ist zu lang.',
    ) {
    }
}
```

`Attribute::TARGET_PROPERTY` begrenzt, wo das Attribut erlaubt ist - hier nur an Eigenschaften. Der Konstruktor nimmt Parameter entgegen, genau wie beim Verwenden weiter unten.

## Mit Parametern verwenden

```php
final class PostInput
{
    public function __construct(
        #[MaxLength(200, message: 'Titel maximal 200 Zeichen.')]
        public string $title = '',
        #[MaxLength(10000)]
        public string $body = '',
    ) {
    }
}
```

Wichtig: Beim Anhängen wird noch nichts geprüft. Das Attribut ist nur hinterlegt. Erst ein Leser holt es sich.

## Per Reflection auslesen

Reflection ist PHPs Werkzeug, um zur Laufzeit Code über sich selbst zu befragen. Ein kleiner Validierer liest die Attribute jeder Eigenschaft:

```php
function validate(object $target): array
{
    $errors = [];
    $reflection = new ReflectionObject($target);

    foreach ($reflection->getProperties() as $property) {
        $value = $property->getValue($target);
        foreach ($property->getAttributes(MaxLength::class) as $attribute) {
            $rule = $attribute->newInstance();
            if (mb_strlen((string) $value) > $rule->max) {
                $errors[$property->getName()] = $rule->message;
            }
        }
    }

    return $errors;
}
```

`getAttributes(MaxLength::class)` filtert auf unser Attribut, `newInstance()` baut die Klasse mit den angegebenen Parametern. Der Rest ist gewöhnliche Logik.

In Knospe passt so ein Muster gut in die Http-Schicht: Der Handler nimmt Eingaben entgegen (siehe [`backend/src/Http/Input.php`](../../backend/src/Http/Input.php)), der Validierer prüft sie, und bei Fehlern fliegt eine `ValidationException` aus `backend/src/Core/Exception`. So bleibt die Regel am Feld, die Prüfung an einer Stelle.

Zurück zum Konzept: [Wann Attribute?](08-attributes-konzept.md).
