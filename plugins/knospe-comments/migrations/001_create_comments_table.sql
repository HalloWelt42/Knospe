-- Plugin-Migration 001: Tabelle "comments"
-- Läuft automatisch beim Aktivieren des Plugins. Verweist auf posts und users;
-- ON DELETE CASCADE sorgt für saubere Aufräumarbeit auf Datenbank-Ebene.

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
