# HTTP-Methoden

Eine HTTP-Anfrage hat immer eine Methode - auch "Verb" genannt. Sie sagt, was mit einer Ressource geschehen soll. Die Methode ist kein Zierrat: Server, Zwischenspeicher und Browser verlassen sich auf ihre Bedeutung. Wer sie richtig einsetzt, bekommt vorhersehbares Verhalten geschenkt.

Die vier wichtigsten in Knospe, direkt aus `backend/config/routes.php`:

- **GET** - lesen, nichts verändern. `GET /api/posts` listet Beiträge, `GET /api/posts/{id}` zeigt einen. GET-Anfragen brauchen kein CSRF-Token und dürfen zwischengespeichert werden.
- **POST** - etwas Neues anlegen oder eine Aktion auslösen. `POST /api/posts` erstellt einen Beitrag, `POST /api/auth/login` meldet an.
- **PUT** - eine bestehende Ressource vollständig ersetzen. `PUT /api/posts/{id}` schreibt Titel und Text neu.
- **DELETE** - eine Ressource entfernen. `DELETE /api/posts/{id}`.

Ein zentraler Begriff dabei ist **Idempotenz**: Eine Methode ist idempotent, wenn dieselbe Anfrage mehrfach ausgeführt dasselbe Ergebnis liefert wie einmal. GET, PUT und DELETE sind idempotent - ein Beitrag zweimal löschen ändert nichts am Endzustand (er ist weg). POST ist **nicht** idempotent: Zweimal `POST /api/posts` erzeugt zwei Beiträge. Deshalb sollte man POST-Anfragen nicht blind wiederholen.

Es gibt noch **PATCH** für teilweise Aenderungen - man schickt nur die geänderten Felder statt der ganzen Ressource. Knospe nutzt PATCH aktuell nicht, aber die `CsrfMiddleware` behandelt es bereits als verändernde Methode:

```php
private const UNSAFE = ['POST', 'PUT', 'PATCH', 'DELETE'];
```

Genau hier zeigt sich der praktische Nutzen der Semantik: Alle verändernden Methoden brauchen ein gültiges CSRF-Token im Header `X-CSRF-Token`, die lesende GET-Anfrage nicht. Der Router prüft die Methode übrigens genau und wirft bei falscher Methode eine `MethodNotAllowedException` (405), nicht einfach 404.

Wie diese Methoden zu einem stimmigen API-Entwurf zusammenpassen, steht in [REST kurz erklärt](09-restful-apis-kurz.md).
