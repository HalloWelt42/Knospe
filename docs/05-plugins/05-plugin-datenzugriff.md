# Plugin mit eigener Tabelle

Ein Plugin soll seine Daten nicht in Kern-Tabellen quetschen, sondern eigene mitbringen. Dafür legt es eine Migration an, die beim Aktivieren läuft.

## Die Migration

Jede SQL-Datei unter `migrations/` gehört dem Plugin. Der Zahl-Präfix bestimmt die Reihenfolge. So legt das Kommentar-Plugin seine Tabelle an ([`plugins/knospe-comments/migrations/001_create_comments_table.sql`](../../plugins/knospe-comments/migrations/001_create_comments_table.sql)):

```sql
CREATE TABLE IF NOT EXISTS comments (
    id         BIGSERIAL PRIMARY KEY,
    post_id    BIGINT      NOT NULL REFERENCES posts (id) ON DELETE CASCADE,
    author_id  BIGINT      NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    content    TEXT        NOT NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_comments_post ON comments (post_id);

-- DOWN:
DROP TABLE IF EXISTS comments;
```

Der Fremdschlüssel mit `ON DELETE CASCADE` räumt auf Datenbank-Ebene mit auf, wenn ein Beitrag verschwindet.

## Wann sie läuft: plugin_migrations

Beim Aktivieren durchsucht `PluginService::runMigrations()` den Ordner `migrations/`. Für jede Datei prüft er in der Tabelle `plugin_migrations`, ob diese Version für dieses Plugin schon lief. Nur neue laufen, danach wird der Eintrag gespeichert:

```sql
CREATE TABLE plugin_migrations (
    plugin_id   VARCHAR(64) NOT NULL,
    version     INTEGER     NOT NULL,
    name        TEXT        NOT NULL,
    executed_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    PRIMARY KEY (plugin_id, version)
);
```

So läuft jede Migration genau einmal, auch wenn man ein Plugin deaktiviert und später wieder aktiviert.

## PDO vom Manager

Für Abfragen gibt es die geteilte PDO-Verbindung über `$manager->db()`, oder - eleganter - der Controller bekommt `PDO` per Konstruktor vom Container. Immer mit vorbereiteten Anweisungen (Prepared Statements) arbeiten:

```php
$stmt = $this->pdo->prepare('INSERT INTO comments (post_id, author_id, content) VALUES (?, ?, ?) RETURNING id');
$stmt->execute([$postId, $user->id, $content]);
```

## Daten bleiben beim Deaktivieren

Wichtig: `disable()` löscht **keine** Daten. Deaktivieren blendet ein Plugin nur aus - die Tabelle `comments` bleibt bestehen, damit beim erneuten Aktivieren nichts verloren ist. Aufräumen ist eine bewusste, separate Entscheidung des Nutzers.

Wie Einstellungen (nicht Tabellen) gespeichert werden, zeigt [Plugin-Einstellungen](06-plugin-konfiguration.md).
