# Codestil (PSR-12)

Ein einheitlicher Codestil sorgt dafür, dass alle Dateien gleich aussehen. Das klingt nach einer Kleinigkeit, ist aber wichtig: Wer Code liest, soll über Inhalt nachdenken, nicht über Formatierung. In Knospe folgt der PHP-Code dem Standard PSR-12. Das WARUM dahinter ist die gemeinsame Sprache: Jeder im Team schreibt so, wie es die Werkzeuge erwarten, und Aenderungen im Verlauf bleiben klein und lesbar.

## Die wichtigsten Regeln

- Einrückung mit 4 Leerzeichen, keine Tabs.
- Oeffnende geschweifte Klammer von Klassen und Methoden steht in der nächsten Zeile, bei Kontrollstrukturen (`if`, `for`) in derselben Zeile.
- Am Dateianfang steht `declare(strict_types=1);`, danach der `namespace`, danach die `use`-Importe (alphabetisch, jede Klasse eine Zeile).
- Sichtbarkeit immer ausschreiben: `public`, `protected`, `private`.

Ein kurzes Beispiel im Stil des Projekts:

```php
<?php

declare(strict_types=1);

namespace Knospe\Domain\Post;

use Knospe\Support\AuthContext;

final class PostService
{
    public function __construct(
        private readonly PostRepositoryInterface $repository,
    ) {
    }

    public function all(): array
    {
        return $this->repository->findAll();
    }
}
```

## Automatisch statt von Hand

Niemand muss diese Regeln auswendig anwenden. PHP-CS-Fixer prüft und korrigiert sie. Zwei Composer-Skripte helfen:

```bash
composer lint       # nur prüfen, was nicht passt
composer lint:fix   # automatisch korrigieren
```

Vor jedem Commit einmal `composer lint` laufen zu lassen ist eine gute Gewohnheit. Wie du Tests und Analyse dazunimmst, steht in [Beitragen](05-beitragen.md). Die statische Prüfung ergänzt den Stil, siehe [Glossar: Entwicklung](glossar-entwicklung.md).
