# Das Service-Muster

Warum landet die Geschäftslogik nicht einfach im Handler? Weil ein Handler dann zu viel auf einmal täte: HTTP lesen, validieren, Rechte prüfen, in die Datenbank schreiben, HTTP antworten. Das wird schnell unübersichtlich und lässt sich kaum testen. Knospe teilt die Arbeit deshalb in drei Schichten mit je einer klaren Verantwortung.

- **Handler** (`backend/src/Http/Handler/...`): Uebersetzt zwischen HTTP und Fachlogik. Liest die Eingabe, ruft den Service, verpackt das Ergebnis als JSON. Sonst nichts.
- **Service** (`backend/src/Domain/.../...Service.php`): Die eigentliche Geschäftslogik. Validierung, Regeln, Rechte. Kennt kein HTTP.
- **Repository** (`...RepositoryInterface`): Der Datenzugriff. Nur SQL, keine Regeln.

So sieht der `CreatePostHandler` aus - er ist bewusst dünn:

```php
public function handle(ServerRequestInterface $request): ResponseInterface
{
    $user = $this->auth->require();
    $data = Input::json($request);

    $post = $this->posts->create(
        $user->id,
        (string) ($data['title'] ?? ''),
        (string) ($data['body'] ?? ''),
    );

    return Json::response(['post' => $post->toArray()], 201);
}
```

Die Regeln stecken im `PostService`, nicht hier:

```php
public function create(int $authorId, string $title, string $body): Post
{
    [$title, $body] = $this->validate($title, $body);
    $post = $this->posts->create($authorId, $title, $body);
    $this->hooks->doAction('post.created', $post);
    return $post;
}
```

Der Service validiert, schreibt über das Repository und löst einen Hook aus, damit Plugins reagieren können. Beim Ändern und Löschen prüft er zusätzlich, ob der aktuelle Nutzer der Autor ist - sonst wirft er eine `ForbiddenException`.

Der Gewinn: Jede Schicht ist für sich testbar. Den Service testest du ohne HTTP, das Repository gegen eine echte Testdatenbank, den Handler kaum, weil er fast nichts tut. Und wenn später eine CLI dieselbe Logik braucht, ruft sie einfach denselben Service.

Details zu den Verträgen der Datenschicht: [Dependency Injection](04-dependency-injection.md). Fehler aus dem Service werden zentral behandelt, siehe [Zentrale Fehlerbehandlung](07-exception-handling-zentral.md).
