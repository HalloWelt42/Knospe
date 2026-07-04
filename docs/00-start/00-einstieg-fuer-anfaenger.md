# Einstieg für Anfänger

Willkommen. Dieser Leitfaden setzt keine Vorkenntnisse voraus. Er bringt dich von "Ich habe den Ordner heruntergeladen" bis zum ersten grünen Start.

## Was ist ein Terminal?

Ein Terminal ist ein Fenster, in das du Textbefehle tippst, statt auf Knöpfe zu klicken. Auf macOS heißt es "Terminal", unter Linux oft "Konsole". Du gibst eine Zeile ein und drückst die Eingabetaste. Ein Befehl wie `./knospe status` heißt: "Führe das Programm `knospe` mit dem Wort `status` aus." Das Programm ist ein kleines Skript, das im Projekt liegt und im Kern nur dokumentierte Befehle bündelt - siehe `knospe`.

## Was macht Docker?

Docker verpackt PHP, PostgreSQL und die Weboberfläche in abgeschottete Behälter (Container). So läuft alles gleich, egal welcher Rechner darunter steht, und du musst nichts einzeln von Hand installieren. Der Vorteil für dich: ein Befehl startet den kompletten Baukasten.

## Projekt öffnen

Öffne ein Terminal und wechsle in den Projektordner:

```
cd ~/Entwicklung/Knospe
```

`cd` bedeutet "change directory", also Ordner wechseln. Ab jetzt beziehen sich alle Befehle auf diesen Ordner.

## Erster grüner Lauf

Zwei Befehle genügen:

```
./knospe setup
./knospe up
```

`setup` erzeugt eine [`.env`](../../.env) mit eindeutigen Ports und baut die Container - das dauert beim ersten Mal ein paar Minuten. `up` startet dann alles und spielt Datenbank-Migrationen ein. Danach zeigt dir das Skript die Adressen:

```
./knospe status
```

Dort steht zum Beispiel die Frontend-Adresse und eine PHP-API-Adresse mit `/api/health`. Rufst du diese im Browser auf und siehst eine kurze Statusmeldung, läuft alles.

## Wo geht es weiter?

- Warum dieses Projekt existiert: [Was ist Knospe?](01-was-ist-knospe.md)
- Details zum Docker-Start: [Schnellstart mit Docker](02-installation-docker.md)
- Ohne Docker arbeiten: [Installation ohne Docker](03-installation-bare-metal.md)
- Welche Datei wozu: [Datei-Übersicht](04-datei-uebersicht.md)
