-- Migration 002: Tabelle "posts"
-- Beitraege gehoeren zu einem Nutzer (Fremdschluessel author_id). Wird der
-- Nutzer geloescht, verschwinden seine Beitraege mit (ON DELETE CASCADE).
-- Lern mehr: docs/03-datenbank/07-beziehungen-foreign-keys.md

CREATE TABLE IF NOT EXISTS posts (
    id         BIGSERIAL PRIMARY KEY,
    author_id  BIGINT       NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    title      VARCHAR(255) NOT NULL,
    body       TEXT         NOT NULL,
    created_at TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at TIMESTAMPTZ  NOT NULL DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_posts_author  ON posts (author_id);
CREATE INDEX IF NOT EXISTS idx_posts_created ON posts (created_at DESC);

-- DOWN:
DROP TABLE IF EXISTS posts CASCADE;
