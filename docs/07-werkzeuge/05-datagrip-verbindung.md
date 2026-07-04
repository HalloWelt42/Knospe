# DataGrip verbinden

DataGrip ist ein Werkzeug für Datenbanken. Damit siehst du direkt, welche Tabellen und Zeilen Knospe in PostgreSQL abgelegt hat, ohne Umwege über die Anwendung. Das hilft enorm beim Verstehen: Du kannst nachschauen, was ein Registrieren oder ein neuer Beitrag wirklich in der Datenbank bewirkt.

## Den richtigen Port herausfinden

Die Postgres-Datenbank läuft im Container. Nach aussen ist sie über einen Host-Port erreichbar. Dieser Port ist nicht fest: Das Skript `tools/setup-env.sh` leitet ihn pfad-eindeutig ab (per `cksum` in die `.env`), damit sich zwei Installationen von Knospe nie in die Quere kommen.

Den tatsächlich vergebenen Port zeigt dir:

```
./knospe status
```

Merke dir den dort genannten PostgreSQL-Port. Genau diesen brauchst du gleich.

## Datenquelle anlegen

1. Oeffne in DataGrip das Datenbank-Panel und klicke auf das Plus, dann "Data Source > PostgreSQL".
2. Trage bei "Host" `localhost` ein.
3. Trage bei "Port" den Port aus `./knospe status` ein.
4. Fülle "User", "Password" und "Database" mit den Werten aus der `.env` (`POSTGRES_USER`, `POSTGRES_PASSWORD`, `POSTGRES_DB`).

Warum `localhost` und ein gemappter Port? Weil DataGrip auf deinem Mac läuft und dort die Datenbank über die nach aussen gereichte Portnummer sichtbar ist, nicht über den internen Container-Namen.

## Verbindung testen

Klicke auf "Test Connection". Erscheint ein grünes Häkchen, steht die Verbindung. Beim ersten Mal bietet DataGrip an, den PostgreSQL-Treiber herunterzuladen - bestätige das.

## Das Schema erkunden

Klappe die Datenquelle auf. Unter "public" findest du die Tabellen, die die Migrationen angelegt haben (etwa `users` und `posts`), sowie die Verwaltungstabellen `migrations` und `plugin_migrations`. Ein Doppelklick auf eine Tabelle zeigt ihre Daten.

Wie du damit gezielt Abfragen schreibst, steht in [SQL in DataGrip](06-sql-queries-in-datagrip.md).
