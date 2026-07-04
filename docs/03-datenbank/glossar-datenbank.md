# Glossar: Datenbank

Kurze Erklärungen der wichtigsten Begriffe rund um die Datenbank in Knospe. Jeder Eintrag verweist auf das passende Kapitel.

## ACID

Vier Eigenschaften, die eine verlässliche Datenbank garantiert: Atomicity (eine Transaktion läuft ganz oder gar nicht), Consistency (Regeln wie Fremdschlüssel bleiben gewahrt), Isolation (gleichzeitige Vorgänge stören sich nicht) und Durability (bestätigte Daten bleiben erhalten). PostgreSQL erfüllt ACID. Praktisch spürst du das bei [Transaktionen](03-pdo-und-sql.md).

## Normalisierung

Daten so aufteilen, dass jede Information nur an einer Stelle steht. Statt den Autorennamen in jeden Beitrag zu kopieren, steht er einmal in `users` und wird per Fremdschlüssel verknüpft. Das WARUM: keine Widersprüche, einfache Änderungen. Siehe [Beziehungen und Fremdschlüssel](07-beziehungen-foreign-keys.md).

## Index

Eine Zusatzstruktur, die das Suchen beschleunigt - wie ein Stichwortverzeichnis im Buch. `CREATE INDEX idx_posts_created ON posts (created_at DESC)` macht das Sortieren nach Datum schnell. Ohne Index müsste die Datenbank alle Zeilen durchgehen. Angelegt wird er in [Erste Tabelle anlegen](05-erste-tabelle-anlegen.md).

## Fremdschlüssel (Foreign Key)

Eine Spalte, die auf eine Zeile in einer anderen Tabelle verweist, zum Beispiel `posts.author_id` auf `users.id`. Die Datenbank sorgt dafür, dass der Verweis gültig bleibt. Mit `ON DELETE CASCADE` wird beim Löschen automatisch aufgeräumt. Details in [Beziehungen und Fremdschlüssel](07-beziehungen-foreign-keys.md).

## Prepared Statement

Eine Abfrage mit Platzhaltern (`?`), bei der die Werte getrennt übergeben werden. Die Datenbank behandelt sie immer als Daten, nie als Befehl - der zentrale Schutz gegen SQL-Injection. Siehe [PDO und sichere Abfragen](03-pdo-und-sql.md).

## Migration

Ein nummerierter, versionierter Schritt, der das Schema verändert (Tabelle anlegen, Spalte hinzufügen). Knospe verwaltet sie über `schema_migrations` und führt jede in einer eigenen Transaktion aus. Siehe [Migrationen](04-migrationen-system.md).

## Weitere Stichworte

- Primärschlüssel: eindeutiger Bezeichner einer Zeile, hier `BIGSERIAL PRIMARY KEY`.
- Constraint: Regel wie `NOT NULL`, `UNIQUE`, `DEFAULT`.
- Repository: Zugriffsschicht, die das Wie kapselt, siehe [Das Repository-Muster](09-repository-pattern.md).
