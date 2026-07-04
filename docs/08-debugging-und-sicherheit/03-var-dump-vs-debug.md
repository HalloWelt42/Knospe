# var_dump vs. Debugger

Du willst wissen, welchen Wert eine Variable an einer bestimmten Stelle hat. Dafür gibt es drei Wege. Jeder hat seinen Platz - die Kunst ist, den passenden zu wählen.

## Der schnelle Blick: var_dump

`var_dump()` schreibt Typ und Inhalt einer Variable direkt in die Ausgabe:

```php
$post = $this->repository->find($id);
var_dump($post); // zeigt: NULL oder das Post-Objekt mit allen Feldern
```

Das ist unschlagbar schnell für eine einzelne, kurze Frage. Aber es hat Nachteile: Bei einer JSON-API zerstört die Ausgabe die Antwort, du musst den Aufruf wieder entfernen, und du siehst nur genau diese eine Variable. Merke: `var_dump` gehört nie in einen Commit. Nutze es zum Schauen, dann lösch es.

## Der gründliche Weg: der Debugger

Xdebug hält das Programm an einer von dir gesetzten Stelle an und lässt dich **alle** Variablen inspizieren, Schritt für Schritt weitergehen und sogar Ausdrücke auswerten - ohne den Code zu ändern. Das lohnt sich, sobald eine Frage mehr als eine Variable betrifft oder du den Ablauf nicht durchschaust. Wie du das einrichtest und bedienst, steht in [Breakpoints und Schrittausführung](05-breakpoints-und-stepping.md).

## Der dauerhafte Weg: Logging

Wenn dich etwas nur unter bestimmten Bedingungen oder im Betrieb interessiert, schreibst du es ins Protokoll. Das Projekt bringt Monolog mit; ein Logger ist über den Container verfügbar:

```php
$this->logger->debug('Post geladen', ['id' => $id, 'gefunden' => $post !== null]);
```

Solche Zeilen dürfen bleiben, laufen auch in Produktion und lassen sich später mit `./knospe logs php` wiederfinden, siehe [Fehlerprotokolle lesen](02-error-log-lesen.md).

## Wann was

- **var_dump**: einmalige, lokale Frage nach einem Wert. Danach löschen.
- **Debugger**: du willst den Ablauf verstehen oder mehrere Variablen sehen.
- **Logging**: die Information ist auch später oder im Betrieb wertvoll.

Faustregel: Sobald du zum zweiten Mal ein `var_dump` an dieselbe Stelle setzt, nimm den Debugger.
