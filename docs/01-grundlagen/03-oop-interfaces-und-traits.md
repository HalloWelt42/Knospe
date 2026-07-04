# Interfaces und Traits

Ein Interface ist ein Versprechen. Es legt fest, welche Methoden eine Klasse haben MUSS, sagt aber nichts darüber, wie sie umgesetzt werden. Das WARUM: Der aufrufende Code verlässt sich nur auf das Versprechen, nicht auf eine bestimmte Klasse. Dadurch werden Teile austauschbar und testbar.

## Interface als Versprechen

In Knospe beschreibt `PostRepositoryInterface` in [`backend/src/Domain/Post/PostRepositoryInterface.php`](../../backend/src/Domain/Post/PostRepositoryInterface.php), wie Beiträge gespeichert und geladen werden - ohne zu verraten, dass dahinter PostgreSQL steckt.

```php
interface PostRepositoryInterface
{
    public function find(int $id): ?Post;
    public function save(Post $post): Post;
}
```

Die echte Umsetzung liefert `PostgresRepository`. Der `PostService` kennt aber nur das Interface. Warum das gut ist: Im Test kannst du eine einfache Attrappe einsetzen, die dieselben Methoden hat, aber im Speicher arbeitet - ohne echte Datenbank. Der Service merkt den Unterschied nicht. Genauso funktioniert `PluginInterface` in [`backend/src/Plugin/PluginInterface.php`](../../backend/src/Plugin/PluginInterface.php): Der Kern arbeitet mit jedem Plugin, das dieses Versprechen einlöst.

Eine Klasse gibt mit `implements` an, dass sie ein Interface erfüllt:

```php
final class PostgresRepository implements PostRepositoryInterface { /* ... */ }
```

## Traits

Ein Trait ist ein Baustein mit fertigem Code, den mehrere Klassen einbinden können, ohne voneinander zu erben. Das löst ein praktisches Problem: Eine Klasse kann nur von einer einzigen Elternklasse erben, aber beliebig viele Traits nutzen. So teilst du kleine Hilfsfunktionen zwischen Klassen, die sonst nichts gemeinsam haben.

```php
trait TimestampsTrait
{
    public function touch(): void { $this->updatedAt = new DateTimeImmutable(); }
}

final class SomeEntity { use TimestampsTrait; }
```

Kurz gesagt: Interface = Versprechen über Methoden, Trait = geteilter Code. Wie ein Interface durch Vererbung mit einer abstrakten Klasse kombiniert wird, zeigt [Vererbung](02-oop-vererbung.md).
