# Spickzettel: SQL/PostgreSQL

PostgreSQL 16 speichert alle Daten von Knospe. Das Backend spricht per PDO mit der Datenbank (`backend/src/Database/Connection.php`). Wer die Abfragen versteht, versteht auch, was die Repositories tun.

## Die vier Grundbefehle

```sql
SELECT id, title FROM posts WHERE author_id = 1 ORDER BY created_at DESC;
INSERT INTO posts (title, body) VALUES ('Hallo', 'Text');
UPDATE posts SET title = 'Neu' WHERE id = 5;
DELETE FROM posts WHERE id = 5;
```

Merke: `WHERE` grenzt ein. Ein `UPDATE` oder `DELETE` ohne `WHERE` trifft die ganze Tabelle - das ist fast nie gewollt.

## JOIN

Ein JOIN verbindet Zeilen aus zwei Tabellen über einen gemeinsamen Schlüssel. So holst du zum Beitrag gleich den Autor.

```sql
SELECT p.title, u.email
FROM posts p
JOIN users u ON u.id = p.author_id;
```

## Constraints und Datentypen

| Constraint | Bedeutung |
| --- | --- |
| `PRIMARY KEY` | eindeutiger Schlüssel je Zeile |
| `NOT NULL` | Wert ist Pflicht |
| `UNIQUE` | kein Wert doppelt |
| `REFERENCES` | Fremdschlüssel auf andere Tabelle |

| Datentyp | Nutzung |
| --- | --- |
| `serial` / `bigserial` | automatische ID |
| `text` / `varchar` | Text |
| `timestamptz` | Zeitpunkt mit Zeitzone |
| `boolean` | true/false |

Diese Typen findest du in `database/migrations`.

## psql-Basics

Im Container erreichst du die Datenbank mit `psql`:

| Befehl | Wirkung |
| --- | --- |
| `\l` | Datenbanken auflisten |
| `\dt` | Tabellen zeigen |
| `\d posts` | Struktur einer Tabelle |
| `\q` | beenden |

Weiter: [Datenbank und Migrationen](../../03-datenbank/06-sql-basics.md). Für eine grafische Sicht siehe [DataGrip einrichten](../../07-werkzeuge/05-datagrip-verbindung.md). Referenz: postgresql.org.
