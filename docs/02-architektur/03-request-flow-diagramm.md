# Der Weg einer Anfrage

Jede HTTP-Anfrage nimmt in Knospe denselben, offen sichtbaren Weg. Es gibt keine versteckte Magie: Du kannst den ganzen Ablauf in [`backend/public/index.php`](../../backend/public/index.php) und [`backend/src/Core/Kernel.php`](../../backend/src/Core/Kernel.php) nachlesen. Wer diesen Weg einmal versteht, versteht das ganze Backend.

Der Front-Controller ist der einzige Einstiegspunkt. Er lädt den Autoloader, liest die [`.env`](../../.env), baut die Konfiguration, erzeugt den Kernel und wandelt die PHP-Globals in ein PSR-7-Anfrageobjekt um. Dann übergibt er die Anfrage an den Kernel und sendet am Ende die Antwort zurück.

```
    HTTP-Anfrage
        |
        v
  public/index.php          Front-Controller: Autoloader, .env, PSR-7-Request
        |
        v
  config/config.php         Config aus Umgebungsvariablen
        |
        v
  Core/Kernel               Container fuellen, Plugins booten, Routen laden
        |
        v
  Core/Pipeline             Middleware-Kette (siehe unten)
   |  ErrorHandlingMiddleware   faengt alle Fehler ab
   |  CsrfMiddleware            prüft Token bei POST/PUT/PATCH/DELETE
        |
        v
  Core/Router               Pfad + Methode -> Handler (Platzhalter wie {id})
        |
        v
  Http/Handler/...          liest Eingabe, ruft Service
        |
        v
  Domain/.../Service        Geschäftslogik, Validierung, Rechte
        |
        v
  Domain/.../Repository     SQL gegen PostgreSQL
        |
        v
  Http/Json                 baut die JSON-Antwort (PSR-7)
        |
        v
    HTTP-Antwort            zurück durch die Pipeline zum Client
```

Wichtig ist die Richtung: Die Anfrage läuft von oben nach unten durch die Pipeline zum Router, die Antwort denselben Weg zurück nach oben. So kann die `ErrorHandlingMiddleware` ganz vorne wirklich jeden Fehler dahinter einfangen. Der Router steht am Ende der Kette und ist selbst ein PSR-15-Handler.

Ein konkreter `POST /api/posts` durchläuft also: CSRF-Prüfung, Router findet `CreatePostHandler`, dieser ruft `PostService::create()`, das validiert und schreibt über das Repository, und `Json::response(...)` liefert Statuscode 201 zurück.

Vertiefen: [Middleware-Pipeline](06-middleware-pipeline.md), [Das Service-Muster](05-services-pattern.md) und [Zentrale Fehlerbehandlung](07-exception-handling-zentral.md).
