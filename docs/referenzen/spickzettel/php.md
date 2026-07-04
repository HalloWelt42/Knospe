# Spickzettel: PHP

PHP 8.5 ist die Sprache des Knospe-Backends. Dieser Spickzettel bündelt das, was du in `backend/src` immer wieder siehst. Das Warum steht kurz dabei, damit du die Muster nicht nur abschreibst, sondern verstehst.

## Grundsyntax

| Zweck | Beispiel |
| --- | --- |
| Variable | `$name = 'Knospe';` |
| Typisiert (Funktion) | `function add(int $a, int $b): int` |
| Nullable-Typ | `?string` (String oder null) |
| Named Argument | `new Post(title: 't', body: 'b')` |
| Null-Safe-Zugriff | `$user?->email` |
| Null-Koaleszenz | `$config['port'] ?? 5432` |

## OOP und moderne Klassen

Der Kern nutzt konsequent Konstruktor-Promotion und `readonly`. Das spart Boilerplate und schützt Entitäten vor nachträglicher Änderung.

```php
final class Post
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $body,
    ) {}
}
```

`readonly` heißt: nach dem Setzen unveränderbar. Damit ist eine Entity ein sicherer Wert, den du gefahrlos herumreichst. Siehe `backend/src/Domain/Post/Post.php`.

## Enums und Attribute

```php
enum Role: string
{
    case Admin = 'admin';
    case User  = 'user';
}
```

Enums ersetzen lose Strings durch eine feste Auswahl. Attribute (`#[...]`) hängen Metadaten an Code, etwa Routen-Definitionen.

## Häufige Funktionen

| Arrays | String |
| --- | --- |
| `array_map`, `array_filter` | `str_contains`, `str_starts_with` |
| `array_keys`, `in_array` | `trim`, `explode`, `implode` |
| `count`, `array_merge` | `sprintf`, `json_encode` |

Vertiefung: [Klassen und Objekte](../../01-grundlagen/01-oop-klassen-intro.md), offizielle Referenz auf php.net. Wie diese Bausteine zusammenspielen, zeigt der [Kern-Überblick](../../02-architektur/02-ordner-bedeutung-detailliert.md).
