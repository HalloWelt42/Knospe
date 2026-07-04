# Wann Attribute?

Attribute sind strukturierte Metadaten, die direkt am Code hängen - an Klassen, Methoden, Eigenschaften oder Parametern. Sie beschreiben etwas über den Code, ohne selbst ausgeführt zu werden. Erst ein Leser (per Reflection) entscheidet, was mit diesen Angaben geschieht. Das WARUM: Zusatzinformationen stehen dort, wo sie gelten, sind maschinenlesbar und typgeprüft.

## Unterschied zu Docblocks

Früher wanderten solche Angaben in Kommentare, sogenannte Docblocks:

```php
/**
 * @Route("/api/posts", methods={"GET"})
 */
public function list(): Response { /* ... */ }
```

Das Problem: Ein Kommentar ist für PHP nur Text. Tippfehler bemerkt niemand, der Editor bietet keine Vervollständigung, und das Auslesen braucht einen eigenen Parser. Ein Attribut dagegen ist echter PHP-Code in eckigen Klammern:

```php
#[Route('/api/posts', methods: ['GET'])]
public function list(): Response { /* ... */ }
```

Es wird beim Kompilieren geprüft, der Editor kennt es, und es lässt sich sauber über Reflection lesen. Docblocks bleiben sinnvoll für reine Beschreibung für Menschen (`@param`, Erklärungen); Attribute sind für Angaben, die das Programm verarbeiten soll.

## Typische Einsatzfelder

- **Routing:** Statt einer zentralen Routen-Liste hängt der Pfad am Handler. In Knospe stehen Routen bewusst gebündelt in `backend/config/routes.php`. Ein attribut-basierter Ansatz wäre eine Alternative - beides ist gängig.
- **Validierung:** Ein Attribut an einer Eigenschaft sagt, welche Regel gilt, etwa `#[NotBlank]` oder `#[MaxLength(200)]`. Der Validierer liest die Attribute und prüft die Werte. Vergleiche die `ValidationException` in `backend/src/Core/Exception`.
- **Rechte:** `#[RequiresRole('admin')]` an einer Methode macht die Zugriffsregel sichtbar. Eine Middleware wertet sie aus, bevor der Handler läuft - passend zur Auth-Idee in `backend/src/Support/AuthContext.php`.

Der rote Faden: Das WAS (die Regel) steht deklarativ am Code, das WIE (die Auswertung) liegt an einer zentralen Stelle. So bleibt der Handler lesbar.

Wie man Attribute selbst schreibt und ausliest, zeigt [Attribute in der Praxis](09-attributes-praxis.md).
