# Der Pipe-Operator

Verschachtelte Funktionsaufrufe liest man verkehrt herum: Der Aufruf, der zuletzt wirkt, steht ganz außen. Der Pipe-Operator `|>` dreht das um und legt eine Kette an, die man von links nach rechts liest - in genau der Reihenfolge, in der die Daten durchlaufen. Das WARUM: Lesbarkeit. Der Weg des Wertes wird zur Leserichtung.

## Verschachtelt versus Kette

Klassisch schachtelt man Aufruf um Aufruf:

```php
$slug = strtolower(trim(str_replace(' ', '-', $title)));
```

Um zu verstehen, was zuerst passiert, muss das Auge nach innen zur Mitte springen und dann nach außen zurück. Mit dem Pipe-Operator liest sich derselbe Ablauf als Kette:

```php
$slug = $title
    |> str_replace(' ', '-', ...)
    |> trim(...)
    |> strtolower(...);
```

Der Wert links wird in die Funktion rechts als Argument gegeben, das Ergebnis fließt weiter. Reihenfolge und Leserichtung stimmen jetzt überein: erst ersetzen, dann trimmen, dann kleinschreiben.

Gut kombinierbar ist das mit den [First-Class-Callables](10-first-class-callables.md): `trim(...)` ist der typsichere Verweis auf die Funktion. Auch eigene Methoden lassen sich einreihen, etwa wenn ein `Post` aus [`backend/src/Domain/Post/Post.php`](../../backend/src/Domain/Post/Post.php) weiterverarbeitet wird.

## Abgrenzung zum Bitwise-Oder

Vorsicht bei der Optik: `|>` ist nicht dasselbe wie das einzelne `|`. Das einfache `|` ist der bitweise Oder-Operator und rechnet auf einzelnen Bits:

```php
$flags = READ | WRITE;   // bitweises Oder, verknüpft Bit-Masken
$slug  = $title |> trim(...);   // Pipe, reicht Werte durch eine Kette
```

Zwei völlig verschiedene Aufgaben. Der Pipe-Operator hat zwei Zeichen (`|>`) und arbeitet mit ganzen Werten und Funktionen; das bitweise Oder hat ein Zeichen (`|`) und arbeitet mit Zahlen auf Bit-Ebene. Wer sie verwechselt, bekommt entweder einen Syntaxfehler oder ein völlig falsches Ergebnis - also bewusst hinschauen.

## Wann einsetzen

Sinnvoll ist die Kette, wenn ein Wert mehrere klar getrennte Schritte durchläuft und jeder Schritt eine kleine, fokussierte Funktion ist. Genau das ist die Knospe-Linie: kleine Funktionen, klare Namen. Bei nur einem Aufruf lohnt die Kette nicht - dann ist der direkte Aufruf klarer.

Zurück zur Übersicht: [Glossar: Grundlagen](glossar-grundlagen.md).
