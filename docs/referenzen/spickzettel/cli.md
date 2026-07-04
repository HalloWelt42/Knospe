# Spickzettel: Terminal

Das Terminal ist die Kommandozeile deines Rechners. Bei Knospe steuerst du damit das Projekt-Skript, arbeitest in Containern und siehst dir Dateien an. Die folgenden Befehle reichen für den Alltag.

## Bewegen und ansehen

| Befehl | Wirkung |
| --- | --- |
| `cd ordner` | in einen Ordner wechseln |
| `cd ..` | eine Ebene höher |
| `ls` / `ls -la` | Inhalt anzeigen (auch versteckte) |
| `pwd` | aktuellen Pfad zeigen |
| `cat datei` | Datei komplett ausgeben |
| `less datei` | Datei blätterbar ansehen (`q` beendet) |

## Suchen mit grep

`grep` findet Text in Dateien. So findest du schnell, wo eine Route definiert ist:

```bash
grep -r "api/posts" backend/
```

`-r` sucht rekursiv durch alle Unterordner.

## Pipes

Eine Pipe `|` reicht die Ausgabe eines Befehls an den nächsten weiter. So filterst du lange Ausgaben:

```bash
docker compose logs | grep error
```

## Rechte

Dateien können ausführbar sein. Das Projekt-Skript braucht dieses Recht:

```bash
chmod +x knospe    # ausführbar machen
./knospe status    # ausführen
```

Das `./` sagt: führe das Skript aus dem aktuellen Ordner aus.

## Im Container arbeiten

Oft läuft ein Befehl nicht auf deinem Rechner, sondern im PHP-Container, wo PHP und Composer bereitstehen:

```bash
docker compose exec php bash        # Shell im Container
docker compose exec php composer test
```

Innerhalb des Containers liegt das Projekt unter `/app`. Vertiefung: [Docker-Spickzettel](docker.md) und [Das knospe-Skript](../../06-deployment/02-docker-befehle-alltag.md).
