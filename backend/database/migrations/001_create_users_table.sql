-- Migration 001: Tabelle "users"
-- Legt die Nutzer-Tabelle an. Passwörter werden NIE im Klartext, sondern
-- nur als Hash gespeichert (siehe PasswordHasher / Argon2id).
-- Lern mehr: docs/03-datenbank/05-erste-tabelle-anlegen.md

CREATE TABLE IF NOT EXISTS users (
    id            BIGSERIAL PRIMARY KEY,
    email         VARCHAR(255) NOT NULL UNIQUE,
    display_name  VARCHAR(120) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at    TIMESTAMPTZ  NOT NULL DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_users_email ON users (email);

-- DOWN:
DROP TABLE IF EXISTS users CASCADE;
