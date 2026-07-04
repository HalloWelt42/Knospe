# Code-Inspektion

PhpStorm liest deinen Code mit und meldet Auffälligkeiten, noch bevor du das Programm startest. Diese Prüfungen heissen Inspektionen. Sie reichen von echten Fehlern (Tippfehler in einem Methodennamen) bis zu Hinweisen (eine Variable wird nie benutzt). Für Einsteiger sind sie ein Sicherheitsnetz, das viele Fehler frueh sichtbar macht.

## Wo die Hinweise erscheinen

Auffälligkeiten unterstreicht PhpStorm direkt im Editor. Rechts neben dem Text zeigt ein Streifen die Verteilung im ganzen File: rot für Fehler, gelb für Warnungen. Fährst du mit der Maus darüber, erklärt ein Text das Problem.

## PHPStan-Ergebnisse verstehen

Knospe prüft den Code zusätzlich mit PHPStan auf Level 6. PHPStan ist eine statische Analyse: Es führt den Code nicht aus, sondern schließt aus Typen und Aufrufen auf mögliche Fehler. Auf der Kommandozeile startest du es mit `composer analyse`.

Typische Meldungen betreffen Typen, etwa "Method returns string but returns int" oder ein möglicherweise null-Wert, der nicht behandelt wird. Weil unsere Entitäten in `backend/src/Domain/` als `readonly` mit klaren Typen definiert sind, fällt PHPStan schnell auf, wenn irgendwo ein falscher Typ durchrutscht. Nimm solche Meldungen ernst - sie zeigen fast immer eine echte Lücke.

PhpStorm kann die PHPStan-Ergebnisse auch inline anzeigen, wenn du PHPStan in "Settings > PHP > Quality Tools" auf den Docker-Interpreter zeigen lässt. Dann siehst du die Analyse direkt am Code statt nur im Terminal.

## Quick-Fix und Reformat

Steht der Cursor auf einer markierten Stelle, drücke Alt+Enter. PhpStorm bietet passende Lösungen an ("Quick-Fix"), etwa einen fehlenden Import ergänzen oder einen Typ korrigieren. Wähle die passende aus.

Zum sauberen Einrücken und Formatieren dient "Code > Reformat Code" (Strg+Alt+L bzw. Cmd+Alt+L). Beachte: Verbindliche Formatierung leistet in Knospe PHP-CS-Fixer nach PSR-12, siehe [PHP-CS-Fixer nutzen](08-php-cs-fixer-in-phpstorm.md). Das IDE-Reformat ist der schnelle Griff zwischendurch, der Fixer das letzte Wort.
