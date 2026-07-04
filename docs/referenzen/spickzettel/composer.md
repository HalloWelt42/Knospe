# Spickzettel: Composer

Composer ist der Paketverwalter für PHP. Er lädt die Abhängigkeiten von Knospe (etwa nyholm/psr7, monolog, phpdotenv) und richtet das Autoloading ein, damit du Klassen ohne manuelles `require` nutzen kannst.

## Pakete verwalten

| Befehl | Wirkung |
| --- | --- |
| `composer install` | Abhängigkeiten aus der Sperrdatei installieren |
| `composer update` | auf neue erlaubte Versionen aktualisieren |
| `composer require paket/name` | neues Paket hinzufügen |
| `composer require --dev paket` | nur für Entwicklung/Tests |

`install` folgt der Datei `composer.lock` und liefert exakt reproduzierbare Versionen. `update` verschiebt diese Grenzen und schreibt die Sperrdatei neu.

## Autoloading (PSR-4)

Knospe bildet den Namensraum `Knospe\` auf `backend/src` ab. Das steht in `backend/composer.json`:

```json
"autoload": {
    "psr-4": { "Knospe\\": "src/" }
}
```

Damit wird `Knospe\Domain\Post\Post` automatisch aus `src/Domain/Post/Post.php` geladen. Nach Änderungen an dieser Zuordnung hilft:

```bash
composer dump-autoload
```

## Skripte

Die wiederkehrenden Aufgaben stehen als Skripte in `composer.json`, sodass alle denselben Befehl nutzen:

| Befehl | Wirkung |
| --- | --- |
| `composer test` | PHPUnit-Tests ausführen |
| `composer analyse` | PHPStan (Level 6) prüfen |
| `composer lint` | Stil prüfen (PSR-12) |
| `composer lint:fix` | Stil automatisch korrigieren |

Die Befehle laufen am besten im PHP-Container (`docker compose exec php composer test`). Vertiefung: [Abhängigkeiten und Autoloading](../../02-architektur/02-ordner-bedeutung-detailliert.md) und [Qualitätswerkzeuge](../../09-entwicklung/01-code-style-psr-12.md).
