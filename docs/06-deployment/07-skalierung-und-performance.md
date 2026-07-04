# Skalierung und Leistung

Solange Knospe auf deinem Rechner läuft, ist Leistung selten ein Thema. Sobald viele Nutzer gleichzeitig zugreifen, wird sie es. Dieser Überblick zeigt die wichtigsten Stellschrauben - nicht als Rezept, sondern damit du weißt, wo man ansetzt, wenn es langsam wird.

## Zuerst messen, dann optimieren

Optimiere nie ins Blaue. Finde erst die langsame Stelle: Ist es eine Datenbankabfrage, das Rendern im Frontend oder die Anzahl der Anfragen? Die Netzwerk-Ansicht des Browsers und die PHP-Logs (siehe [Logs und Fehlersuche](03-logs-und-debugging.md)) zeigen, wo Zeit verloren geht.

## Datenbank: Indizes

Die häufigste Ursache für langsame Abfragen sind fehlende Indizes. Ohne Index muss PostgreSQL die ganze Tabelle durchsuchen. Ein Index auf einer oft gefilterten oder sortierten Spalte macht das drastisch schneller:

```sql
CREATE INDEX idx_posts_created_at ON posts (created_at);
```

Solche Indizes gehören in eine Migration unter `database/migrations`, damit sie überall gleich entstehen.

## Abfragen schlank halten

- Nur die Spalten holen, die du wirklich brauchst, statt `SELECT *`.
- Das klassische N+1-Problem vermeiden: nicht in einer Schleife pro Zeile eine weitere Abfrage abfeuern, sondern möglichst in einer Abfrage laden.

## Caching (Ausblick)

Werte, die sich selten ändern, muss man nicht bei jeder Anfrage neu berechnen. Ein Cache hält das Ergebnis kurz vor. Knospe bringt das nicht mit - aber die klare Trennung über `Service`- und `Repository`-Klassen (etwa `backend/src/Domain/Post`) macht es leicht, einen Cache später genau an dieser Stelle einzuziehen.

## Mehrere PHP-Prozesse

Der eingebaute PHP-Server bearbeitet Anfragen nacheinander. Für echten Betrieb setzt man PHP-FPM ein, das viele Prozesse parallel hält und so mehrere Nutzer gleichzeitig bedient (siehe [Betrieb ohne Docker](04-bare-metal-installation.md)). Weil der Zustand in der Datenbank liegt und nicht im Prozess, lassen sich mehrere Prozesse - und später mehrere Server - ohne Umbau nebeneinander betreiben.
