# Property Hooks (PHP 8.4)

Manchmal soll eine Eigenschaft nicht einfach nur einen Wert speichern, sondern beim Lesen etwas berechnen oder beim Schreiben etwas prüfen. Property Hooks (seit PHP 8.4) erlauben genau das: Du hängst direkt an eine Eigenschaft einen `get`- oder `set`-Block. Das WARUM: Die Logik steht dort, wo sie hingehört - an der Eigenschaft selbst - statt in separaten Methoden, die man leicht zu rufen vergisst.

## get-Hook: berechnete Eigenschaft

```php
final class UserProfile
{
    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {}

    public string $fullName {
        get => trim("$this->firstName $this->lastName");
    }
}

$p = new UserProfile('Ada', 'Lovelace');
echo $p->fullName; // Ada Lovelace
```

`fullName` speichert nichts. Der Wert entsteht bei jedem Lesezugriff frisch aus Vor- und Nachname. Du greifst darauf zu wie auf jede andere Eigenschaft - mit `->fullName`, ohne Klammern.

## set-Hook: beim Schreiben prüfen

```php
public string $email {
    set (string $value) {
        $this->email = strtolower(trim($value));
    }
}
```

Beim Zuweisen wird der Wert erst aufbereitet, dann abgelegt.

## Unterschied zu Magic Methods

PHP kennt schon länger die Magic Methods `__get()` und `__set()`. Die greifen aber nur bei Eigenschaften, die gar nicht deklariert sind, und gelten für alle auf einmal. Property Hooks sind das Gegenteil: Sie gehören zu einer klar benannten, deklarierten Eigenschaft. Dadurch weiss die Entwicklungsumgebung genau, was existiert, die statische Analyse (siehe [Klassen und Objekte](01-oop-klassen-intro.md) für den Klassenkontext) kann prüfen, und der Code bleibt lesbar.

## Wann sinnvoll

Setze Hooks ein, wenn ein Wert stets aus anderen Daten abgeleitet ist oder beim Schreiben immer normalisiert werden soll. Für reine, unveränderliche Datenträger wie die Entitäten in Knospe reicht dagegen `readonly` - siehe [Constructor Promotion und readonly](04-properties-constructor-promotion.md).
