# Enums in der Praxis

Ein Enum (Aufzählung) fasst eine feste, endliche Menge erlaubter Werte in einem eigenen Typ zusammen. Statt einen Beitrag-Status als losen String `"published"` durch den Code zu reichen, gibt es einen Typ, der nur genau die vorgesehenen Fälle kennt. Das WARUM: Der Editor kennt alle Fälle, Tippfehler fliegen sofort auf, und ungültige Zustände können gar nicht erst entstehen.

## Pure und backed

Ein reines Enum (pure) hat nur benannte Fälle ohne dahinterliegenden Wert:

```php
enum Role
{
    case Admin;
    case Author;
    case Guest;
}
```

Ein backed Enum hinterlegt je Fall einen Wert (String oder Int) - praktisch, wenn der Wert aus der Datenbank kommt oder ins JSON soll:

```php
enum PostStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
}
```

`PostStatus::from('published')` liefert den passenden Fall, `tryFrom('xyz')` gibt `null` statt eines Fehlers. `->value` gibt den hinterlegten String zurück, `PostStatus::cases()` alle Fälle als Array.

## Methoden im Enum

Ein Enum ist mehr als eine Liste: Es kann Methoden tragen und Interfaces erfüllen. So wohnt Logik dort, wo sie hingehört:

```php
enum PostStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Entwurf',
            self::Published => 'Veroeffentlicht',
        };
    }

    public function isVisible(): bool
    {
        return $this === self::Published;
    }
}
```

## Nutzen im Code

In Knospe wäre ein solcher Status ein sauberer Typ auf einem `Post` (siehe `backend/src/Domain/Post/Post.php`). Der Vorteil: `if ($status->isVisible())` ist selbsterklärend, und `match` ohne `default` zwingt dazu, jeden neuen Fall auch zu behandeln - vergisst man einen, meckert die Analyse ([PHPStan](../09-entwicklung/04-testing-kultur.md)).

Weiter geht es mit [Wann Attribute?](08-attributes-konzept.md).
