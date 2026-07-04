# SQL in DataGrip

Sobald die Datenquelle steht (siehe [DataGrip verbinden](05-datagrip-verbindung.md)), kannst du der Datenbank direkt Fragen stellen. SQL ist die Sprache dafür. In DataGrip geschriebene Abfragen sind meist schneller und übersichtlicher als der Umweg über die Kommandozeile mit `psql` im Container.

## Eine SQL-Konsole öffnen

Klicke die Datenquelle mit der rechten Maustaste an und wähle "New > Query Console". Es öffnet sich ein Editor, in dem du SQL tippst. Die Konsole ist bereits mit deiner Knospe-Datenbank verbunden.

## Eine Abfrage ausführen

Schreibe eine Abfrage und führe sie mit Strg+Enter (bzw. Cmd+Enter) aus. Steht der Cursor in einer von mehreren Anweisungen, läuft nur die aktuelle.

```sql
SELECT id, title, created_at
FROM posts
ORDER BY created_at DESC
LIMIT 10;
```

Das zeigt die zehn neuesten Beiträge. Willst du sehen, wer registriert ist:

```sql
SELECT id, email, created_at FROM users;
```

Das Passwort erscheint dabei nur als Argon2id-Hash, niemals im Klartext - genau so soll es sein.

## Ergebnisse ansehen und verstehen

Unten öffnet sich eine Tabelle mit dem Ergebnis. Du kannst Spalten sortieren, filtern und einzelne Zellen anschauen. So prüfst du zum Beispiel, ob ein `POST /api/posts` wirklich eine Zeile eingefügt hat.

## Exportieren

Über das Kontextmenü des Ergebnisses ("Export Data") speicherst du die Ausgabe als CSV oder als SQL-Insert. Das ist praktisch, um Testdaten festzuhalten oder Ergebnisse weiterzugeben.

## Vorsicht bei Änderungen

`SELECT` liest nur und ist ungefährlich. Anweisungen wie `UPDATE` oder `DELETE` verändern Daten. Für strukturierte, wiederholbare Änderungen sind in Knospe die Migrationen unter `database/migrations` und der Seed unter `database/seeds` gedacht (ausgeführt per `./knospe migrate` und `./knospe seed`). Nutze die SQL-Konsole zum Nachschauen und Experimentieren, nicht als Ersatz für Migrationen.
