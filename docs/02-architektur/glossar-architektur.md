# Glossar: Architektur

Diese Kurzdefinitionen sammeln die Begriffe, die in der Architektur-Doku immer wieder auftauchen. Jeder Eintrag verweist auf die Stelle, an der er ausführlich erklärt wird.

**Front-Controller** - Der einzige Einstiegspunkt für alle HTTP-Anfragen. In Knospe ist das [`backend/public/index.php`](../../backend/public/index.php). Statt viele einzelne PHP-Dateien direkt aufzurufen, läuft alles durch diese eine Datei, die den immer gleichen Ablauf startet. Siehe [Der Weg einer Anfrage](03-request-flow-diagramm.md).

**Namespace** - Ein Präfix, das Klassennamen eindeutig macht und Kollisionen vermeidet. Knospe nutzt `Knospe\`, per PSR-4 auf `backend/src` abgebildet. `Knospe\Domain\Post\PostService` liegt also in `Domain/Post/PostService.php`. Siehe [PSR-Standards](01-psr-standards-intro.md).

**Request / Response** - Anfrage und Antwort als Objekte nach PSR-7, nicht als rohe PHP-Globals. Der `Request` trägt Methode, Pfad, Header und Rumpf; die `Response` trägt Statuscode, Header und Rumpf. Siehe [Der Weg einer Anfrage](03-request-flow-diagramm.md).

**Middleware** - Ein Stück Code, das sich zwischen Anfrage und Handler hängt und Querschnittsaufgaben erledigt (Fehler abfangen, CSRF prüfen). Mehrere Middleware bilden eine Kette. Siehe [Middleware-Pipeline](06-middleware-pipeline.md).

**Container** - Der Ort, an dem Dienste gebaut und ihre Abhängigkeiten automatisch verkabelt werden ([`backend/src/Core/Container.php`](../../backend/src/Core/Container.php), PSR-11). Statt `new` überall zu streuen, holt man Dienste aus dem Container. Siehe [Dependency Injection](04-dependency-injection.md).

**Handler** - Die dünne Schicht, die eine einzelne Route bedient: Eingabe lesen, Service rufen, JSON antworten. Jeder Handler folgt dem PSR-15-Vertrag mit `handle()`. Siehe [Das Service-Muster](05-services-pattern.md).

**Service** - Trägt die Geschäftslogik einer Domäne (`PostService`, `UserService`): Validierung, Regeln, Rechte. Kennt kein HTTP. Siehe [Das Service-Muster](05-services-pattern.md).

**Repository** - Kapselt den Datenzugriff hinter einem Interface. Der Service kennt nur den Vertrag, nicht das SQL. Siehe [Dependency Injection](04-dependency-injection.md).

**Idempotenz** - Eigenschaft einer Methode, bei der mehrfaches Ausführen dasselbe Ergebnis liefert wie einmaliges. GET, PUT und DELETE sind idempotent, POST nicht. Siehe [HTTP-Methoden](08-http-method-semantik.md).

**Ressource** - Das "Ding", das eine REST-API adressiert (z.B. ein Beitrag unter `/api/posts/{id}`). Die Methode bestimmt die Aktion, der Pfad die Ressource. Siehe [REST kurz erklärt](09-restful-apis-kurz.md).
