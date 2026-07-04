# Spickzettel: Git

Git verwaltet die Versionsgeschichte von Knospe. Es merkt sich jede Änderung, sodass du zurückgehen, vergleichen und parallel arbeiten kannst. Dieser Spickzettel zeigt die Befehle für den Alltag.

## Zustand ansehen

| Befehl | Wirkung |
| --- | --- |
| `git status` | was ist geändert oder vorgemerkt |
| `git diff` | konkrete Zeilenänderungen |
| `git log --oneline` | Verlauf kompakt |

## Ändern und festhalten

```bash
git add datei.php        # Änderung vormerken
git add .                # alles Geänderte vormerken
git commit -m "Nachricht" # Vormerkung als Punkt festhalten
```

Ein Commit ist ein Speicherpunkt mit Beschreibung. Die Nachricht sagt, warum etwas geändert wurde, nicht nur was.

## Branches

Ein Branch ist eine parallele Arbeitslinie. So testest du etwas, ohne den Hauptstand zu stören.

| Befehl | Wirkung |
| --- | --- |
| `git branch` | Branches auflisten |
| `git switch -c feature` | neuen Branch anlegen und wechseln |
| `git switch main` | zurück auf main |
| `git merge feature` | Branch in den aktuellen einführen |

## Mit dem Server abgleichen

| Befehl | Wirkung |
| --- | --- |
| `git pull` | Änderungen vom Server holen |
| `git push` | eigene Commits hochladen |

## Typischer Ablauf

```bash
git switch -c feature/plugins
# Dateien ändern
git add .
git commit -m "Plugin-Ansicht ergänzt"
git switch main
git merge feature/plugins
git push
```

So bleibt die Geschichte nachvollziehbar: kleine Commits mit klaren Nachrichten. Vertiefung: [Versionsverwaltung](../../07-werkzeuge/09-git-integration.md).
