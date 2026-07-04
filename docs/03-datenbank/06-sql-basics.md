# SQL-Grundlagen

SQL ist die Sprache, mit der du mit der Datenbank redest. Vier Befehle deckst du fast immer ab: lesen, einfügen, ändern, löschen. Alle Beispiele hier passen zum Knospe-Schema (Tabellen `users`, `posts`, `comments`).

## Lesen: SELECT

```sql
SELECT id, title, created_at
FROM posts
WHERE author_id = 1
ORDER BY created_at DESC
LIMIT 10;
```

- `SELECT` wählt die Spalten (oder `*` für alle).
- `WHERE` filtert Zeilen nach einer Bedingung.
- `ORDER BY ... DESC` sortiert, hier nach Datum absteigend (neueste zuerst).
- `LIMIT 10` begrenzt auf zehn Zeilen. Das WARUM: nie unbegrenzt alles laden.

## Einfügen: INSERT

```sql
INSERT INTO posts (author_id, title, body)
VALUES (1, 'Mein erster Beitrag', 'Hallo Welt');
```

Du nennst die Spalten und die Werte. `id`, `created_at` und `updated_at` füllt die Datenbank selbst (siehe die `DEFAULT`-Regeln in der Migration).

## Aendern: UPDATE

```sql
UPDATE posts
SET title = 'Neuer Titel', updated_at = now()
WHERE id = 5;
```

Wichtig: Ohne `WHERE` würde jede Zeile geändert. Immer die Bedingung setzen.

## Löschen: DELETE

```sql
DELETE FROM posts WHERE id = 5;
```

Auch hier gilt: Ohne `WHERE` ist die ganze Tabelle leer. Vorsicht.

## Tabellen verbinden: JOIN

Ein Beitrag kennt nur `author_id`, nicht den Namen. Mit einem `JOIN` holst du den Namen aus `users` dazu - genau so macht es `PostgresPostRepository`:

```sql
SELECT p.title, u.display_name AS author_name
FROM posts p
JOIN users u ON u.id = p.author_id
ORDER BY p.created_at DESC;
```

`ON u.id = p.author_id` sagt, wie die Zeilen zusammengehören. Mehr zu solchen Verbindungen im Kapitel [Beziehungen und Fremdschlüssel](07-beziehungen-foreign-keys.md).

Zum Ausprobieren eignet sich [DataGrip](08-datagrip-einrichtung.md). In PHP schreibst du dieselben Abfragen als Prepared Statements, siehe [PDO und sichere Abfragen](03-pdo-und-sql.md).
