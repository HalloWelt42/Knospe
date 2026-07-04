# Das Repository-Muster

Der restliche Code soll sich nicht darum kümmern müssen, wie Daten gespeichert werden - nur darum, was er will: "gib mir die neuesten Beiträge", "lege einen an". Genau das leistet das Repository-Muster. Es trennt das Was (ein Interface) vom Wie (eine konkrete Umsetzung).

## Der Vertrag: das Interface

`backend/src/Domain/Post/PostRepositoryInterface.php` beschreibt nur die Methoden, ohne SQL:

```php
interface PostRepositoryInterface
{
    /** @return list<Post> */
    public function recent(int $limit = 50): array;
    public function findById(int $id): ?Post;
    public function create(int $authorId, string $title, string $body): Post;
    public function update(int $id, string $title, string $body): Post;
    public function delete(int $id): void;
}
```

Der Service arbeitet nur gegen dieses Interface. Er weiß nicht, ob dahinter PostgreSQL, ein Testspeicher oder etwas anderes steckt.

## Die Umsetzung für PostgreSQL

`backend/src/Domain/Post/PostgresPostRepository.php` erfüllt den Vertrag mit echten Prepared Statements:

```php
public function findById(int $id): ?Post
{
    $stmt = $this->pdo->prepare(self::SELECT . ' WHERE p.id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    return $row === false ? null : Post::fromRow($row);
}
```

Die Konstante `SELECT` verbindet `posts` per `JOIN` mit `users`, damit der Autorenname gleich mitkommt (siehe [SQL-Grundlagen](06-sql-basics.md)). Alle Werte laufen über Platzhalter - kein Injection-Risiko, siehe [PDO und sichere Abfragen](03-pdo-und-sql.md).

## Warum Austauschbarkeit wertvoll ist

Weil der Rest der Anwendung nur das Interface kennt, kannst du die Umsetzung tauschen, ohne anderen Code anzufassen. In den Tests etwa läuft alles gegen eine echte, aber pro Test in einer Transaktion zurückgerollte Datenbank - der Service bleibt unverändert. Das WARUM: klare Grenzen, leichter testbar, später leichter änderbar.

Welches Repository der Container liefert, wird zentral verdrahtet (Autowiring, siehe `backend/src/Core/`). So hängt alles am Vertrag, nicht an der konkreten Klasse.
