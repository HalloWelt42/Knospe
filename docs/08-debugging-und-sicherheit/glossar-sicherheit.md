# Glossar: Sicherheit

Kurz und einsteigerfreundlich erklärt. Jeder Begriff verweist auf die passende Seite mit Beispiel aus Knospe.

## Injection

Fremder Code (meist SQL) wird über eine Eingabe eingeschleust, weil Werte roh in einen Befehl geklebt werden. Gegenmittel: Prepared Statements, also Werte als Parameter statt als Text. Siehe `src/Domain/Post/PostgresRepository.php` und [Sicherheits-Grundlagen](07-sicherheits-basics.md).

## XSS (Cross-Site-Scripting)

Fremdes JavaScript landet in der Ausgabe und läuft im Browser des Opfers. Gegenmittel: Ausgabe escapen. Svelte escapet Text in `{ausdruck}` automatisch, gefährlich bleibt nur `{@html ...}`. Siehe [Sicherheits-Grundlagen](07-sicherheits-basics.md).

## CSRF (Cross-Site-Request-Forgery)

Eine fremde Seite löst im Namen des eingeloggten Nutzers eine verändernde Anfrage aus. Gegenmittel: ein geheimes Token, das nur die echte Oberfläche kennt und bei POST/PUT/PATCH/DELETE mitschickt. Siehe [`src/Http/Middleware/CsrfMiddleware.php`](../../backend/src/Http/Middleware/CsrfMiddleware.php) und [CORS verstehen](10-cors-verstehen.md).

## Authentifizierung

Die Frage "Wer bist du". Belegt wird sie in Knospe über den Login und ein Sitzungs-Cookie (`HttpOnly`, `SameSite=Lax`). Siehe [`src/Support/Session.php`](../../backend/src/Support/Session.php).

## Autorisierung

Die Frage "Was darfst du". Kommt nach der Authentifizierung und prüft Rechte, etwa ob ein Nutzer einen fremden Post löschen darf. Beides ist getrennt zu behandeln. Siehe [Sicherheits-Grundlagen](07-sicherheits-basics.md).

## Hash

Eine Einweg-Funktion: Aus der Eingabe entsteht ein Fingerabdruck, der sich nicht zurückrechnen lässt. Für Passwörter braucht es einen absichtlich langsamen Hash (Argon2id), niemals md5 oder sha256. Siehe [`src/Support/PasswordHasher.php`](../../backend/src/Support/PasswordHasher.php) und [Passwörter sicher speichern](08-passwort-sicherheit.md).

## Salt

Ein zufälliger Zusatz, der vor dem Hashen ans Passwort kommt, damit gleiche Passwörter verschiedene Hashes ergeben. `password_hash` erzeugt den Salt selbst und legt ihn im Hash-String ab. Siehe [Passwörter sicher speichern](08-passwort-sicherheit.md).

## TLS / HTTPS

Verschlüsselter Transport zwischen Browser und Server. Schützt Vertraulichkeit, Integrität und Echtheit (per Zertifikat). Siehe [HTTPS und TLS](09-https-und-ssl.md).

## CORS

Browser-Regel, die Antworten fremder Herkünfte verbirgt. Kein Ersatz für CSRF-Schutz. Siehe [CORS verstehen](10-cors-verstehen.md).

## Rate-Limiting

Begrenzt die Zahl der Anfragen je Absender im Zeitfenster und wehrt so Brute-Force ab; abgelehnt wird mit Status 429. Siehe [Rate-Limiting](11-rate-limiting.md).

## Secrets

Geheimnisse wie Schlüssel und Datenbank-Zugänge. Gehören in die Umgebung ([`.env`](../../.env) via [`src/Support/Config.php`](../../backend/src/Support/Config.php)), nie in den Code und nie ins Log. Siehe [Logging richtig](12-logging-best-practices.md).
