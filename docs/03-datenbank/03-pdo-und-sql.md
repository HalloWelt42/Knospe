# PDO und sichere Abfragen

PDO (PHP Data Objects) ist die eingebaute Brücke zwischen PHP und der Datenbank. Knospe nutzt kein großes Datenbank-Werkzeug, sondern PDO direkt - sichtbar und nachvollziehbar. Die zentrale Stelle ist [`backend/src/Database/Connection.php`](../../backend/src/Database/Connection.php).

## Sichere Voreinstellungen

```php
return new PDO($dsn, $config->dbUser, $config->dbPassword, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
```

- `ERRMODE_EXCEPTION`: Datenbankfehler werden als Exception geworfen, nicht still verschluckt. So merkst du Probleme sofort.
- `FETCH_ASSOC`: Ergebniszeilen kommen als assoziatives Array (`$row['title']`), nicht doppelt.
- `EMULATE_PREPARES=false`: echte Prepared Statements auf Datenbank-Seite - der wichtigste Schutz.

## Prepared Statements gegen SQL-Injection

Werte gehören nie direkt in den SQL-Text. Stattdessen setzt du Platzhalter (`?`) und übergibst die Werte getrennt. Aus `PostgresPostRepository`:

```php
$stmt = $this->pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();
```

Die Datenbank behandelt `$id` immer als Wert, nie als Befehl. Selbst wenn jemand `1; DROP TABLE posts` eingibt, passiert nichts Böses - genau das verhindert SQL-Injection. Das WARUM: Trennung von Code (der Abfrage) und Daten (den Werten).

## Transaktionen - alles oder nichts

Manchmal müssen mehrere Schritte gemeinsam gelingen oder gemeinsam scheitern. Eine Transaktion klammert sie:

```php
$this->pdo->beginTransaction();
try {
    $this->pdo->exec($sql);
    $this->pdo->commit();
} catch (\Throwable $e) {
    $this->pdo->rollBack();
    throw $e;
}
```

Genau dieses Muster nutzt der `MigrationRunner` (siehe [Migrationen](04-migrationen-system.md)): Jede Migration läuft in einer eigenen Transaktion, damit ein Fehler das Schema nicht halb kaputt zurücklässt.

Nachschlagen kannst du alle PDO-Methoden auf php.net.
