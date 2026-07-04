# Beziehungen und Fremdschlüssel

Daten hängen zusammen: Ein Beitrag gehört zu einer Nutzerin, ein Kommentar zu einem Beitrag. Diese Zusammenhänge modellierst du mit Fremdschlüsseln. Das WARUM: Die Datenbank selbst wacht dann darüber, dass die Verweise gültig bleiben - kein Beitrag ohne Autor, kein Kommentar ins Leere.

## Ein Fremdschlüssel mit REFERENCES

In [`backend/database/migrations/002_create_posts_table.sql`](../../backend/database/migrations/002_create_posts_table.sql) steht:

```sql
CREATE TABLE IF NOT EXISTS posts (
    id        BIGSERIAL PRIMARY KEY,
    author_id BIGINT NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    title     VARCHAR(255) NOT NULL,
    body      TEXT NOT NULL
);
```

`author_id BIGINT NOT NULL REFERENCES users (id)` heißt: In `author_id` darf nur eine `id` stehen, die es in `users` wirklich gibt. Versuchst du einen Beitrag mit einer unbekannten `author_id` einzufügen, lehnt die Datenbank das ab. So bleiben die Daten konsistent.

## ON DELETE CASCADE

Was passiert mit den Beiträgen, wenn die Nutzerin gelöscht wird? `ON DELETE CASCADE` beantwortet das: Ihre Beiträge verschwinden automatisch mit. Ohne diese Regel würde die Datenbank das Löschen verweigern, weil noch Verweise offen sind. CASCADE räumt sauber auf - auf Datenbank-Ebene, ohne PHP-Code.

## Die Kette users -> posts -> comments

Das Kommentar-Plugin ([`plugins/knospe-comments/migrations/001_create_comments_table.sql`](../../plugins/knospe-comments/migrations/001_create_comments_table.sql)) hängt eine dritte Ebene an:

```sql
CREATE TABLE IF NOT EXISTS comments (
    id        BIGSERIAL PRIMARY KEY,
    post_id   BIGINT NOT NULL REFERENCES posts (id) ON DELETE CASCADE,
    author_id BIGINT NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    content   TEXT NOT NULL
);
```

Ein Kommentar verweist auf einen Beitrag und auf eine Nutzerin. Wird ein Beitrag gelöscht, verschwinden seine Kommentare mit; wird eine Nutzerin gelöscht, verschwinden ihre Beiträge - und über die Kette auch die Kommentare an diesen Beiträgen. Eine Löschung räumt die ganze Kette auf, ganz von selbst.

Beim Lesen holst du zusammengehörige Zeilen mit einem `JOIN` wieder zusammen, siehe [SQL-Grundlagen](06-sql-basics.md). Begriffe findest du im [Glossar](glossar-datenbank.md).
