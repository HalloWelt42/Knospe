# Passwörter sicher speichern

Passwörter sind das wertvollste, was ein Nutzer uns anvertraut - und oft dasselbe Passwort nutzt er woanders wieder. Deshalb darf ein Passwort niemals im Klartext in der Datenbank liegen und auch nicht mit einem schnellen Verfahren gehasht werden.

## Warum nicht md5 oder sha256

Diese Verfahren sind für Geschwindigkeit gebaut. Genau das ist bei Passwörtern falsch: Ein Angreifer, der die Datenbank erbeutet, kann Milliarden Kandidaten pro Sekunde durchprobieren. Ein guter Passwort-Hash ist absichtlich langsam und speicherhungrig, damit Ausprobieren teuer wird.

## Argon2id in Knospe

PHP bringt alles Nötige mit. Unser dünner Wrapper ist [`src/Support/PasswordHasher.php`](../../backend/src/Support/PasswordHasher.php):

```php
public static function hash(string $plain): string
{
    return password_hash($plain, PASSWORD_ARGON2ID);
}

public static function verify(string $plain, string $hash): bool
{
    return password_verify($plain, $hash);
}
```

`password_hash` erzeugt bei jedem Aufruf einen zufälligen Salt und packt Verfahren, Parameter und Salt in den Ergebnis-String. Es gibt also keine separate Salt-Spalte - alles steht im Hash selbst.

## verify immer aufrufen (Timing)

Ein feiner, aber wichtiger Punkt beim Login: Auch wenn es den Nutzer gar nicht gibt, sollte `password_verify` gegen einen Dummy-Hash laufen. Sonst antwortet der Server bei unbekannten E-Mails messbar schneller als bei bekannten, und ein Angreifer könnte daran gültige Konten erkennen. Diesen Timing-Ausgleich macht [`src/Domain/User/UserService.php`](../../backend/src/Domain/User/UserService.php) in `authenticate()`.

```php
// niemals früh mit "return false" abbrechen, nur weil der Nutzer fehlt
if ($user === null) {
    PasswordHasher::verify($plain, self::DUMMY_HASH);
    return null;
}
```

## Faustregeln

- Nur `password_hash` und `password_verify` verwenden, nie selbst basteln.
- Nie eigenes Salt oder Pfeffer dazufummeln, das schwächt oft eher.
- Bei jedem Login gegen einen Hash rechnen, auch bei unbekanntem Konto.

Der Demo-Zugang ist demo@knospe.local / passwort - der Hash dazu entsteht beim Seeding, siehe [Sicherheits-Grundlagen](07-sicherheits-basics.md).
