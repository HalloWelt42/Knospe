-- Migration 003: Tabellen für das Plugin-System
--   plugins            - welche Plugins bekannt und aktiviert sind
--   plugin_options     - Einstellungen je Plugin (Schlüssel/Wert)
--   plugin_migrations  - welche Plugin-Migrationen schon liefen (je Plugin)
-- Lern mehr: docs/05-plugins/05-plugin-datenzugriff.md

CREATE TABLE IF NOT EXISTS plugins (
    id            VARCHAR(64)  PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    version       VARCHAR(32)  NOT NULL,
    enabled       BOOLEAN      NOT NULL DEFAULT false,
    discovered_at TIMESTAMPTZ  NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS plugin_options (
    plugin_id    VARCHAR(64)  NOT NULL,
    option_key   VARCHAR(120) NOT NULL,
    option_value TEXT,
    PRIMARY KEY (plugin_id, option_key)
);

CREATE TABLE IF NOT EXISTS plugin_migrations (
    plugin_id   VARCHAR(64) NOT NULL,
    version     INTEGER     NOT NULL,
    name        TEXT        NOT NULL,
    executed_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    PRIMARY KEY (plugin_id, version)
);

-- DOWN:
DROP TABLE IF EXISTS plugin_migrations;
DROP TABLE IF EXISTS plugin_options;
DROP TABLE IF EXISTS plugins;
