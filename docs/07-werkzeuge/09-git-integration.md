# Git in PhpStorm

Git verwaltet die Versionsgeschichte deines Codes: Wer hat wann was geändert, und wie kommst du bei Bedarf zurück. PhpStorm bringt Git direkt in die IDE, sodass du nicht ständig ins Terminal wechseln musst. Für Einsteiger ist die grafische Ansicht oft leichter zu überblicken.

## Aenderungen sehen und committen

Geänderte Dateien zeigt PhpStorm im Fenster "Commit" (links). Dort siehst du pro Datei die konkreten Zeilenänderungen. Ein Commit ist ein gespeicherter Stand mit Beschreibung.

1. Wähle die Dateien aus, die zusammengehören.
2. Schreibe eine kurze, aussagekräftige Commit-Nachricht auf Deutsch.
3. Klicke auf "Commit".

Committe kleine, in sich abgeschlossene Schritte. Das erleichtert späteres Nachvollziehen und Zurückrollen.

## Branch und Merge

Ein Branch ist eine parallele Arbeitslinie. Für ein neues Feature legst du einen Branch an, arbeitest dort und führst ihn später wieder zusammen. Unten rechts zeigt PhpStorm den aktuellen Branch. Ein Klick darauf öffnet ein Menü mit "New Branch" (neu anlegen) und "Merge" (zusammenführen). Bleibt ein Konflikt, öffnet PhpStorm eine Ansicht mit drei Spalten, in der du Zeile für Zeile entscheidest, welche Version gilt.

## Private Git-Identität

Achte darauf, dass deine lokale Git-Identität stimmt, bevor du committest. In privaten Projekten wie Knospe verwendest du deine private Identität:

```
git config user.name "HalloWelt42"
git config user.email "my@jobmagnetix.de"
```

So werden Commits der richtigen Person zugeordnet. Diese Einstellung gilt pro Repository; du kannst sie also je Projekt unterschiedlich setzen.

Ein Hinweis zu Knospe: Der Ordner `.idea/` mit den persönlichen IDE-Einstellungen ist per `.gitignore` ausgeschlossen und taucht daher gar nicht erst im Commit-Fenster auf.
