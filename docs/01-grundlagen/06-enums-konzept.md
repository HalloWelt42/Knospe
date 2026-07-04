# Wann Enums?

Oft gibt es für ein Feld nur eine feste Handvoll erlaubter Werte: der Status eines Beitrags ist etwa "draft" oder "published", eine Rolle ist "user" oder "admin". Schreibt man solche Werte als lose Zeichenketten in den Code, entstehen leicht Fehler. Enums lösen das. Das WARUM verstehst du am besten am Problem.

## Das Problem mit Konstanten-Strings

```php
$post->status = 'pubished'; // Tippfehler - fällt niemandem auf
```

Ein solcher Vertipper wird nicht bemerkt, weil jede beliebige Zeichenkette erlaubt ist. Nirgends steht, welche Werte überhaupt gültig sind.

## Enums als Lösung

Ein Enum listet die erlaubten Fälle einmal zentral auf. Danach kann nur noch einer dieser Fälle verwendet werden - ein Tippfehler wird sofort zum Fehler.

```php
enum PostStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
}

$status = PostStatus::Published;
echo $status->value; // published
```

## Pure vs. Backed Enums

- Ein Backed Enum (wie oben, mit `: string`) hat je Fall einen hinterlegten Wert. Das ist ideal, wenn der Wert in die Datenbank geschrieben oder über die API gesendet wird - etwa der Beitragsstatus in Knospe.
- Ein Pure Enum hat keine Werte, nur benannte Fälle. Es passt, wenn die Fälle nur im Programm selbst unterschieden werden und nie gespeichert werden müssen.

## Entscheidungsbaum

- Feste, überschaubare Menge von Fällen, die zusammengehören, und du willst Fehler früh fangen? Nimm ein Enum. Muss der Wert in die Datenbank oder API? Backed Enum. Sonst Pure Enum.
- Ein einzelner unveränderlicher Wert, der nicht zu einer Gruppe gehört (etwa eine Standardgröße)? Nimm eine Konstante mit `const`.
- Freier Text ohne feste Auswahl (etwa ein Beitragstitel)? Bleib bei `string`.

Enums sind eigene Typen und lassen sich in Konstruktoren wie jede andere Eigenschaft ablegen - siehe [Klassen und Objekte](01-oop-klassen-intro.md) und [Constructor Promotion und readonly](04-properties-constructor-promotion.md).
