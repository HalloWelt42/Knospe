# REST kurz erklärt

REST ist eine Art, eine Web-API zu entwerfen. Die Grundidee ist einfach und angenehm: Man denkt in **Ressourcen** (Dingen), nicht in Aktionen. Statt für jede Operation einen eigenen Befehlspfad zu erfinden, gibt es wenige klare Adressen für die Dinge - und die HTTP-Methode sagt, was damit geschehen soll.

Das sieht man in Knospe gut an den Beiträgen ([`backend/config/routes.php`](../../backend/config/routes.php)):

```
GET    /api/posts        alle Beiträge lesen
GET    /api/posts/{id}   einen bestimmten Beitrag lesen
POST   /api/posts        einen neuen Beitrag anlegen
PUT    /api/posts/{id}   einen Beitrag ersetzen
DELETE /api/posts/{id}   einen Beitrag löschen
```

Man beachte: Der Pfad `/api/posts` bleibt gleich, egal ob man liest, anlegt oder löscht. Was passiert, entscheidet das Verb (siehe [HTTP-Methoden](08-http-method-semantik.md)). Es gibt also kein `/api/createPost` oder `/api/deletePostById` - die Methode ist das Verb, der Pfad ist das Substantiv. Das hält die Oberfläche der API klein und vorhersehbar.

Der zweite Baustein sind **Statuscodes**. Die Antwort trägt nicht nur Daten, sondern auch einen Code, der das Ergebnis knapp einordnet:

- 200 - erfolgreich gelesen
- 201 - erfolgreich angelegt (Knospe gibt das beim `POST /api/posts` zurück)
- 400 / 422 - fehlerhafte oder ungültige Eingabe
- 401 / 403 - nicht angemeldet bzw. nicht erlaubt
- 404 - nicht gefunden
- 500 - unerwarteter Serverfehler

Der dritte Baustein ist ein einheitliches **Format**: Knospe spricht durchgehend JSON. Der Helfer [`backend/src/Http/Json.php`](../../backend/src/Http/Json.php) sorgt für den richtigen Content-Type und echte Umlaute:

```php
return new Response(
    $status,
    ['Content-Type' => 'application/json; charset=utf-8'] + $headers,
    $body,
);
```

Damit hat ein Client eine verlässliche Erwartung: Er kennt die Ressourcen, weiß welche Methode was tut, liest den Statuscode und bekommt immer JSON zurück. Diese Vorhersehbarkeit ist der eigentliche Wert von REST - man muss die API nicht auswendig lernen, man kann sie erraten.

Wie die Fehler-Codes zustande kommen, steht in [Zentrale Fehlerbehandlung](07-exception-handling-zentral.md).
