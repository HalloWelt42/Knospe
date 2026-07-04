# Glossar: Grundlagen

Kurze Nachschlage-Erklärungen zu den Begriffen aus diesem Kapitel. Jeder Eintrag sagt in einem Satz, worum es geht, und verweist bei Bedarf auf die ausführliche Seite.

## Objektorientierung

- **Klasse** - Bauplan für Objekte, bündelt Daten und die dazu passenden Methoden. Beispiel: [`backend/src/Domain/Post/Post.php`](../../backend/src/Domain/Post/Post.php).
- **Objekt** - eine konkrete Ausprägung einer Klasse, mit eigenen Werten.
- **Interface** - ein Vertrag, der nur festlegt, welche Methoden es geben muss, nicht wie sie arbeiten. So kann Knospe die Datenzugriffe hinter `PostRepositoryInterface` austauschen. Siehe [`backend/src/Domain/Post/PostRepositoryInterface.php`](../../backend/src/Domain/Post/PostRepositoryInterface.php).
- **Trait** - wiederverwendbarer Block aus Methoden, den mehrere Klassen einmischen können, ohne zu erben. Nützlich gegen Doppelung, wo Vererbung nicht passt.
- **Polymorphismus** - "Vielgestaltigkeit": Derselbe Aufruf verhält sich je nach konkretem Objekt unterschiedlich. Der Router in [`backend/src/Core/Router.php`](../../backend/src/Core/Router.php) ruft `handle()` auf, ohne den genauen Handler zu kennen - jeder Handler antwortet auf seine Weise.

## Sprachbausteine

- **Enum** - fester, endlicher Typ erlaubter Werte, etwa ein Beitrag-Status. Details in [Enums in der Praxis](07-enums-praxis.md).
- **Attribut** - strukturierte Metadaten direkt am Code, per Reflection auswertbar. Details in [Wann Attribute?](08-attributes-konzept.md) und [Attribute in der Praxis](09-attributes-praxis.md).
- **readonly** - eine Eigenschaft, die nach dem Setzen im Konstruktor nicht mehr geändert werden darf. `Post` ist `final readonly` - einmal gebaut, unveränderlich. Das macht Datenklassen berechenbar.
- **First-Class-Callable** - `funktion(...)` als typsicherer Verweis auf eine Funktion. Siehe [First-Class-Callables](10-first-class-callables.md).
- **Pipe-Operator** - `|>` reiht Aufrufe zu einer von links nach rechts lesbaren Kette. Siehe [Der Pipe-Operator](11-pipe-operator.md).

## Leitprinzipien

- **SOLID** - fünf Entwurfsregeln für wartbaren Code; die wichtigste hier: jede Klasse hat genau eine Aufgabe (Single Responsibility). Deshalb sind `Repository`, `Service` und Handler getrennt.
- **DRY** - "Don't Repeat Yourself": gleiche Logik nur einmal schreiben, sonst als Funktion oder Trait bündeln.
- **KISS** - "Keep It Simple": die einfachste Lösung wählen, die die Aufgabe löst. Knospe hat bewusst einen kleinen eigenen Kern statt eines schweren Aufbaus.

Zurück zum ersten Grundlagen-Kapitel: [Enums in der Praxis](07-enums-praxis.md).
