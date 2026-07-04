# Konfiguration und Umgebung

Eine Anwendung läuft in verschiedenen Umgebungen: bei dir lokal, später auf einem Server. Datenbankzugang, Debug-Schalter und Geheimnisse unterscheiden sich dabei. Diese Werte gehören deshalb **nicht in den Code**, sondern in die Umgebung. So bleibt derselbe Code überall gleich - nur die Umgebung ändert sich.

In Knospe kommen die Werte aus **Umgebungsvariablen**. Lokal liest der Front-Controller eine `.env`-Datei ein, im Container liefert Docker Compose die Werte direkt. Der Front-Controller macht das bewusst so, dass gesetzte Werte nicht überschrieben werden:

```php
if (is_file(dirname(__DIR__, 2) . '/.env')) {
    Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2))->safeLoad();
}
```

Statt nun überall verstreut `getenv()` aufzurufen, gibt es ein einziges, typisiertes Konfigurationsobjekt in `backend/src/Support/Config.php`. Es wird über `config/config.php` mit `Config::fromEnv()` gebaut:

```php
public static function fromEnv(): self
{
    return new self(
        appEnv: self::str('APP_ENV', 'production'),
        appDebug: self::bool('APP_DEBUG', false),
        dbHost: self::str('DB_HOST', 'postgres'),
        dbPort: (int) self::str('DB_PORT', '5432'),
        dbName: self::str('DB_NAME', 'knospe'),
        dbUser: self::str('DB_USER', 'knospe'),
        dbPassword: self::str('DB_PASSWORD', ''),
    );
}
```

Der Vorteil: Jeder Wert hat einen klaren Namen und Typ. `dbPort` ist ein `int`, `appDebug` ein `bool`. Fehlt eine Variable, greift ein sinnvoller Standard. Und der Kernel bekommt genau ein `Config`-Objekt übergeben, statt selbst in der Umgebung zu wühlen.

Sicherheit steht dabei im Vordergrund:

- **Geheimnisse nie hardcoden.** Passwörter und Zugangsdaten gehören in die Umgebung, nie in den Quellcode.
- **`.env` gehört nicht ins Repository.** Sie enthält lokale Geheimnisse und bleibt ausgeschlossen.
- **`APP_DEBUG` im Betrieb auf `false`.** Nur so bleiben interne Fehlerdetails verborgen (siehe [Zentrale Fehlerbehandlung](07-exception-handling-zentral.md)).
- **Standard ist der sichere Wert.** `APP_ENV` fällt auf `production`, `APP_DEBUG` auf `false` - vergisst man die Variable, ist man nicht versehentlich im offenen Modus.

Die passenden Ports pro Installation leitet `tools/setup-env.sh` pfad-eindeutig ab und schreibt sie in die `.env`, sodass sich zwei Kopien nie in die Quere kommen. `./knospe status` zeigt die tatsächlichen Werte.
