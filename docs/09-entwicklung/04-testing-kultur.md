# Testkultur

Tests sind kein lästiges Anhängsel, sondern ein Sicherheitsnetz. Sie erlauben dir, Code zu ändern, ohne Angst vor kaputten Stellen. In Knospe liegen die Tests unter `backend/tests` und teilen sich in `tests/Unit` (einzelne Klassen isoliert) und `tests/Integration` (Zusammenspiel mit der Datenbank).

## Aufbau: Arrange, Act, Assert

Ein guter Test folgt drei Schritten. Erst den Ausgangszustand herstellen (Arrange), dann die zu prüfende Aktion ausführen (Act), dann das Ergebnis prüfen (Assert). Diese Struktur macht jeden Test lesbar:

```php
public function test_hashed_password_matches_original(): void
{
    // Arrange
    $hasher = new PasswordHasher();
    $hash = $hasher->hash('passwort');

    // Act
    $isValid = $hasher->verify('passwort', $hash);

    // Assert
    $this->assertTrue($isValid);
}
```

## Aussagekräftige Namen

Der Methodenname soll das erwartete Verhalten beschreiben, nicht die Technik. `test_login_fails_with_wrong_password` sagt sofort, was gilt. `test_login_2` sagt nichts.

## Mock und Fake statt echter Aussenwelt

Ein Unit-Test soll schnell und unabhängig sein. Wo eine Klasse etwa das Repository braucht, gibt man ihr statt der echten Datenbank einen Ersatz: ein Mock prüft, dass eine Methode aufgerufen wurde, ein Fake ist eine einfache funktionierende Attrappe. So testet der Test genau eine Sache.

## Test-Datenbank mit Rollback

Integrationstests brauchen eine echte Datenbank. Damit sie sich nicht gegenseitig stören, startet die gemeinsame `TestCase` jeden Test in einer Transaktion und macht am Ende ein Rollback. Die Datenbank bleibt danach unverändert - kein Aufräumen von Hand nötig.

## Ausführen

```bash
composer test
```

Vor dem Einreichen müssen die Tests grün sein, siehe [Beitragen](05-beitragen.md). Begriffe wie Coverage erklärt das [Glossar: Entwicklung](glossar-entwicklung.md).
