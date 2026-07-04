# Beitragen

Wer an Knospe mitarbeitet, folgt einem einfachen Ablauf. Er sorgt dafür, dass der Hauptzweig `main` jederzeit funktioniert und dass Änderungen nachvollziehbar bleiben. Das Ziel ist nicht Bürokratie, sondern Vertrauen: Jeder soll sich darauf verlassen können, dass ausgecheckter Code läuft.

## 1. Zweig von main

Arbeite nie direkt auf `main`. Erstelle für jede Aufgabe einen eigenen Zweig mit sprechendem Namen:

```bash
git switch -c feature/kommentar-plugin
```

So bleibt deine Arbeit gebündelt und lässt sich einzeln prüfen.

## 2. Aussagekräftige Commits

Ein Commit beschreibt eine abgeschlossene Änderung. Die Nachricht sagt, WAS und WARUM, nicht jede einzelne Zeile:

```
Kommentar-Plugin: Filter comment.content aktiviert

Nutzereingaben werden vor dem Speichern durch den Filter geschickt,
damit Plugins den Inhalt anpassen können.
```

Lieber mehrere kleine, thematisch klare Commits als ein großer Sammel-Commit.

## 3. Grün vor dem Einreichen

Bevor du deinen Zweig zur Uebernahme vorschlägst, müssen drei Werkzeuge zufrieden sein:

```bash
composer test      # alle Tests bestehen
composer lint      # Codestil passt (PSR-12)
composer analyse   # statische Analyse (PHPStan Level 6) ohne Fehler
```

Erst wenn alle drei durchlaufen, ist der Beitrag fertig. Details zu den Tests stehen in [Testkultur](04-testing-kultur.md), zum Stil in [Codestil (PSR-12)](01-code-style-psr-12.md).

## 4. Doku aktualisieren

Aenderst du Verhalten, gehört die Dokumentation dazu. Eine neue API-Route, ein neuer Hook, eine geänderte Standardeinstellung - all das soll in `docs/` stehen, damit die nächste Person nicht raten muss. Code ohne passende Doku gilt als halbfertig.

Wenn du diese vier Punkte einhältst, ist dein Beitrag leicht zu prüfen und schnell aufgenommen. Fachbegriffe klärt das [Glossar: Entwicklung](glossar-entwicklung.md).
