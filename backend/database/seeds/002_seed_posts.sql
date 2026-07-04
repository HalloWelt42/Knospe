-- Seed 002: Beispiel-Beitraege der Demo-Nutzerin.
-- Legt Beitraege nur an, wenn es sie (nach Titel) noch nicht gibt.

INSERT INTO posts (author_id, title, body)
SELECT u.id, v.title, v.body
FROM users u
CROSS JOIN (VALUES
    ('Willkommen bei Knospe',
     'Dies ist ein Beispiel-Beitrag aus den Startdaten. Du kannst ihn bearbeiten oder loeschen.'),
    ('Wie geht es weiter?',
     'Schau ins Handbuch unter docs/ und probiere aus, eigene Beitraege ueber die Oberflaeche anzulegen.')
) AS v (title, body)
WHERE u.email = 'demo@knospe.local'
  AND NOT EXISTS (SELECT 1 FROM posts p WHERE p.title = v.title);
