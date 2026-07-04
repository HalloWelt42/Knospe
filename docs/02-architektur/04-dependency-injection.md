# Dependency Injection

Stell dir vor, ein `PostService` braucht ein Repository, um Beiträge zu speichern. Die naive Lösung: Der Service baut sich das Repository selbst mit `new` im Konstruktor. Das Problem daran ist die feste Verdrahtung. Woher kommt die Datenbankverbindung? Wie tauschst du das Repository im Test gegen eine Attrappe? Wenn eine Klasse ihre Abhängigkeiten selbst beschafft, sind sie global und starr.

Dependency Injection dreht das um: Nicht die Klasse holt sich, was sie braucht, sondern sie bekommt es übergeben. Meist über den Konstruktor. Das nennt man auch "Inversion of Control".

```php
final class PostService
{
    public function __construct(
        private PostRepositoryInterface $posts,
        private HookDispatcher $hooks,
    ) {
    }
}
```

Der Service verlangt ein `PostRepositoryInterface` - also einen Vertrag, keine konkrete Klasse. Wer den Vertrag erfüllt, ist ihm egal. Im echten Betrieb bekommt er das `PostgresPostRepository`, im Test eine In-Memory-Attrappe. So wird Code testbar und flexibel.

Jetzt fehlt nur noch jemand, der all diese Objekte zusammensteckt. Das ist der Container in `backend/src/Core/Container.php`. Er kann per Autowiring den Konstruktor einer Klasse lesen und ihre Abhängigkeiten selbst auflösen:

```php
if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
    $arguments[] = $this->get($type->getName());
    continue;
}
```

Sieht der Container einen Typ-Parameter, holt er ihn rekursiv aus sich selbst. Interfaces werden im `Kernel` gebunden, z.B. `PostRepositoryInterface` auf `PostgresPostRepository`. Jeder Dienst wird nur einmal gebaut (Singleton pro Container).

Wann ist `new` trotzdem in Ordnung? Bei einfachen Wertobjekten ohne Abhängigkeiten - etwa eine `Post`-Entity oder eine `Json`-Antwort. Diese tragen keine Dienste in sich und sind kurzlebig. Die Faustregel: Dienste kommen aus dem Container, Datenobjekte darfst du direkt mit `new` erzeugen.

Weiter mit [Das Service-Muster](05-services-pattern.md).
