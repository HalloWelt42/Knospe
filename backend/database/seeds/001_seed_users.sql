-- Seed 001: Demo-Nutzerin
-- Zugangsdaten fuer die lokale Entwicklung:
--   E-Mail:   demo@knospe.local
--   Passwort: passwort
-- Der gespeicherte Wert ist ein Argon2id-Hash, kein Klartext.

INSERT INTO users (email, display_name, password_hash)
VALUES (
    'demo@knospe.local',
    'Demo-Nutzerin',
    '$argon2id$v=19$m=65536,t=4,p=1$VndQWmtYYnc3b05qV3dmcQ$jO614kaHxtXfuHR2JMNSQ+96+yXnZa0PMm5OGgi73Ps'
)
ON CONFLICT (email) DO NOTHING;
