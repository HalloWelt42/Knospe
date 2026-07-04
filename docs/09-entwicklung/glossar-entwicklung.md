# Glossar: Entwicklung

Dieses Glossar erklärt die Fachbegriffe, die in der Entwicklungs-Dokumentation von Knospe vorkommen. Es ist zum Nachschlagen gedacht: kurze Definitionen, kein Fließtext zum Durchlesen. Wo es passt, verweist ein Eintrag auf die ausführliche Seite.

## Lint / Linting

Automatisches Prüfen des Codes auf Stil- und Formfehler, bevor er ausgeführt wird. In Knospe übernimmt das PHP-CS-Fixer nach dem Standard PSR-12. Aufruf: `composer lint` (nur prüfen) und `composer lint:fix` (korrigieren). Mehr dazu in [Codestil (PSR-12)](01-code-style-psr-12.md).

## Statische Analyse

Das Untersuchen von Code, ohne ihn auszuführen. Ein Analysewerkzeug liest den Quelltext und findet mögliche Fehler - etwa falsche Typen oder unerreichbaren Code. Knospe nutzt PHPStan auf Level 6 (`composer analyse`). Je höher das Level, desto strenger die Prüfung.

## Coverage (Testabdeckung)

Der Anteil des Codes, der beim Ausführen der Tests tatsächlich durchlaufen wird, angegeben in Prozent. Hohe Coverage bedeutet, dass viele Stellen von Tests berührt werden - sie garantiert aber nicht, dass jeder Fall geprüft ist. Ein Orientierungswert, kein Selbstzweck. Siehe [Testkultur](04-testing-kultur.md).

## Semantische Versionierung

Ein Schema für Versionsnummern der Form MAJOR.MINOR.PATCH, zum Beispiel `1.4.2`.

- PATCH (letzte Zahl): Fehlerbehebung, nichts ändert sich am Verhalten.
- MINOR (mittlere Zahl): neue Funktion, die alte weiter funktioniert.
- MAJOR (erste Zahl): Änderung, die bestehenden Code brechen kann.

So sieht man an der Nummer sofort, wie riskant ein Update ist. Auch die `version` im `manifest.json` eines Plugins folgt diesem Schema.

## Weitere Begriffe

- Mock / Fake: Ersatzobjekte im Test, siehe [Testkultur](04-testing-kultur.md).
- Docblock: Kommentarblock mit `@param`, `@return`, `@throws`, siehe [Docblocks und Kommentare](03-docblocks-und-comments.md).
- Commit / Zweig: Einheiten der Versionsverwaltung, siehe [Beitragen](05-beitragen.md).
