# PSR-Standards

PSR steht für "PHP Standard Recommendation". Das sind gemeinsame Vereinbarungen der PHP-Welt, wie bestimmte Dinge aussehen sollen. Warum ist das nützlich? Weil Code so austauschbar wird. Wenn dein Router, dein Autoloader und deine HTTP-Objekte alle die gleichen Verträge sprechen, kannst du einzelne Teile ersetzen, ohne alles umzubauen. Knospe hat KEIN großes Framework, sondern einen eigenen kleinen Kern - und genau deshalb sind die PSR-Standards hier so wichtig: Sie geben dem selbstgebauten Kern ein solides, allgemein verständliches Fundament.

Diese fünf PSRs begegnen dir in Knospe immer wieder:

- **PSR-4 (Autoloading):** Ein Namespace-Präfix wird auf einen Ordner abgebildet. Bei uns `Knospe\` auf `backend/src`. Die Klasse `Knospe\Domain\Post\PostService` liegt also in [`backend/src/Domain/Post/PostService.php`](../../backend/src/Domain/Post/PostService.php). Composer lädt sie automatisch, kein manuelles `require` nötig.
- **PSR-7 (HTTP-Nachrichten):** Anfrage und Antwort sind Objekte mit klaren Methoden, nicht die rohen PHP-Globals. In [`backend/public/index.php`](../../backend/public/index.php) wird die Anfrage per `fromGlobals()` in ein PSR-7-Objekt umgewandelt.
- **PSR-11 (Container):** Ein einheitlicher Vertrag mit `get()` und `has()`, um Dienste zu holen. Unser [`backend/src/Core/Container.php`](../../backend/src/Core/Container.php) implementiert `Psr\Container\ContainerInterface`.
- **PSR-12 (Code-Stil):** Regeln für Einrückung, Klammern und Reihenfolge. PHP-CS-Fixer prüft das automatisch (`composer lint`).
- **PSR-15 (Middleware und Handler):** Ein `RequestHandlerInterface` nimmt eine Anfrage entgegen und gibt eine Antwort zurück; `MiddlewareInterface` hängt sich dazwischen. Router und Pipeline in Knospe folgen genau diesem Muster.

Kurzes Beispiel für den PSR-15-Vertrag, dem jeder Handler folgt:

```php
public function handle(ServerRequestInterface $request): ResponseInterface
{
    return Json::response(['status' => 'ok']);
}
```

Weil alle Handler diese Signatur haben, kann der [Router](03-request-flow-diagramm.md) sie gleich behandeln. Mehr zur Pipeline in [Middleware-Pipeline](06-middleware-pipeline.md).
