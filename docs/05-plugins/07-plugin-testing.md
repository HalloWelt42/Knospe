# Plugins testen

Ein Plugin ist Code wie jeder andere - also wird es getestet. Knospe nutzt PHPUnit mit zwei Ebenen: schnelle Unit-Tests ohne Datenbank und Integrationstests gegen eine echte Test-Datenbank. Beide leben unter `backend/tests/`.

## Unit-Tests für Hooks

Reine Logik testet man ohne Datenbank. Ein Filter etwa ist eine kleine Funktion mit klarer Ein- und Ausgabe:

```php
final class CommentContentFilterTest extends TestCase
{
    public function testTrimmtDenText(): void
    {
        $dispatcher = new HookDispatcher();
        $dispatcher->addFilter('comment.content', static fn (string $t): string => trim($t));

        $result = $dispatcher->applyFilter('comment.content', '  hallo  ');

        self::assertSame('hallo', $result);
    }
}
```

So lässt sich auch die Priorität prüfen: zwei Filter registrieren und die Reihenfolge des Ergebnisses kontrollieren.

## Integrationstest gegen die Test-DB

Ein Controller mit Datenbankzugriff braucht echte Tabellen. Dafür gibt es eine Basisklasse, die jeden Test in einer Transaktion kapselt und am Ende zuruckrollt - so bleibt die Datenbank sauber, egal was der Test einfügt:

```php
final class CommentControllerTest extends TestCase
{
    public function testStoreLegtKommentarAn(): void
    {
        $controller = new CommentController($this->pdo, new HookDispatcher(), $this->auth);

        $response = $controller->store($this->requestWith([
            'post_id' => 1,
            'content' => 'Guter Beitrag',
        ]));

        self::assertSame(201, $response->getStatusCode());
    }
}
```

Weil jeder Test seine Änderungen zuruckrollt, können viele Tests dieselbe Datenbank teilen, ohne sich zu stören. Die Test-Datenbank läuft im Docker-Verbund, nichts berührt den Rest des Rechners.

## Ausführen

Die Composer-Skripte kapseln die üblichen Aufrufe:

```bash
composer test        # PHPUnit (Unit + Integration)
composer analyse     # PHPStan Level 6
composer lint        # PHP-CS-Fixer (PSR-12), Prüfung
composer lint:fix    # dieselben Regeln, aber korrigierend
```

Ein Plugin sollte vor der Weitergabe alle drei bestehen. Wie man es dann teilt, steht in [Plugin weitergeben](08-plugin-veroeffentlichen.md).
