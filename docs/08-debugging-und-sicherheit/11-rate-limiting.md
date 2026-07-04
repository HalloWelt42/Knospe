# Rate-Limiting

Ein Login-Endpunkt ohne Bremse ist eine Einladung: Ein Angreifer probiert einfach tausende Passwörter pro Minute durch (Brute-Force). Argon2id macht jeden einzelnen Versuch teuer (siehe [Passwörter sicher speichern](08-passwort-sicherheit.md)), aber die zweite Verteidigungslinie ist, die Zahl der Versuche selbst zu begrenzen. Genau das leistet Rate-Limiting: Pro Absender werden nur so und so viele Anfragen in einem Zeitfenster zugelassen.

## Das Zähler-Konzept

Die Grundidee ist ein Zähler je Schlüssel. Der Schlüssel ist meist die IP-Adresse, bei Login auch die E-Mail. Für jeden Schlüssel merkt man sich, wie viele Anfragen im aktuellen Zeitfenster kamen. Ist das Limit überschritten, wird abgelehnt, bis das Fenster weiterrückt.

```
Schlüssel: login:203.0.113.7
Fenster:    60 Sekunden
Limit:      10 Versuche
-> 11. Versuch im Fenster wird abgelehnt
```

Der Zähler braucht einen Speicher mit Ablauf. In der Entwicklung genügt oft die Session oder eine Tabelle, in Produktion nimmt man einen schnellen Zwischenspeicher, damit alle Instanzen denselben Stand sehen.

## Die Antwort: 429

Wird das Limit erreicht, ist der richtige Status 429 Too Many Requests. Optional nennt der Header `Retry-After`, nach wie vielen Sekunden es wieder geht. Wichtig: eine klare, aber sparsame Meldung, die nicht verrät, ob das Konto existiert.

## Wo es in der Pipeline sitzt

In Knospe läuft jede Anfrage durch eine PSR-15-Pipeline aus Middleware, siehe `src/Core/Pipeline.php`. Rate-Limiting ist als Middleware ideal aufgehoben: früh in der Kette, noch vor dem eigentlichen Handler. So wird eine zu häufige Anfrage abgewiesen, bevor teure Arbeit wie Datenbankzugriff oder Passwort-Hashing überhaupt beginnt. Als eigene Middleware bleibt es zudem austauschbar - vorbildlich für ein Boilerplate zum Verstehen.

```php
// Skizze einer RateLimitMiddleware
if ($this->store->hits($key) >= $limit) {
    throw new HttpException(429, 'Zu viele Anfragen. Bitte später erneut.');
}
$this->store->increment($key, $windowSeconds);
```

## Faustregeln

- Zuerst limitieren, dann rechnen. Die Bremse gehört an den Anfang der Kette.
- Enge Limits für Login und Registrierung, großzügigere fürs Lesen.
- Immer 429 mit klarer, aber knapper Meldung.

Was dabei protokolliert werden darf, klärt [Logging richtig](12-logging-best-practices.md).
