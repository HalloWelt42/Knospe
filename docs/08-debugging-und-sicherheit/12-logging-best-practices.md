# Logging richtig

Logs sind das Gedächtnis der Anwendung. Wenn nachts etwas schiefgeht, ist das Log oft die einzige Spur. Deshalb lohnt es sich, von Anfang an bewusst zu loggen - nicht zu viel, nicht zu wenig, und vor allem nichts Gefährliches.

## Was loggen

Nützlich sind Ereignisse, die man später verstehen oder zählen will: eingehende Anfragen mit Methode und Pfad, abgelehnte Logins, ausgelöste Fehler, wichtige Zustandsänderungen (Post erstellt, Plugin aktiviert). Für jeden Eintrag gilt die Frage: Hilft mir das um 3 Uhr nachts, ein Problem zu finden.

## Was niemals ins Log gehört

Hier liegt die grösste Gefahr. Niemals ins Log kommen:

- Passwörter, auch nicht gehasht.
- Sitzungs-Token, CSRF-Token, API-Schlüssel.
- Ganze Zugangsdaten oder komplette Request-Bodies mit Geheimnissen.
- Mehr personenbezogene Daten als nötig.

Ein Log ist oft weniger geschützt als die Datenbank und landet schnell in Kopien. Ein Passwort im Klartext-Log macht den ganzen Aufwand aus [Passwörter sicher speichern](08-passwort-sicherheit.md) zunichte.

## Strukturiert loggen

Knospe nutzt monolog. Der große Vorteil: Kontext wird als eigenes Feld übergeben, nicht in den Text hineingebaut. So bleiben Einträge maschinell durchsuchbar.

```php
// gut: Kontext getrennt, keine Geheimnisse
$logger->warning('Login fehlgeschlagen', ['email' => $email, 'ip' => $ip]);

// schlecht: alles im String, Passwort im Log
$logger->info("Login {$email} pw {$password}");
```

In Knospe wird geworfene Fehler zentral in `src/Http/Middleware/ErrorHandlingMiddleware.php` behandelt und dort geloggt - ein guter Ort, weil er jede Anfrage sieht.

## Level bewusst wählen

monolog kennt abgestufte Level. Faustregel für den Alltag:

- debug: nur beim Entwickeln, sehr gesprächig.
- info: normaler Betrieb, erwartete Ereignisse.
- warning: auffällig, aber verkraftbar (fehlgeschlagener Login).
- error: eine Aktion ist gescheitert.
- critical: die Anwendung selbst ist bedroht.

In Produktion filtert man meist ab info oder warning, damit das Log nicht überläuft.

## Faustregeln

- Lieber wenige aussagekräftige Einträge als viele nichtssagende.
- Geheimnisse gehören nie ins Log.
- Kontext als Feld, nicht als Textbrei.

Zum Nachschlagen der Begriffe: [Glossar Sicherheit](glossar-sicherheit.md).
