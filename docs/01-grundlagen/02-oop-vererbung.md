# Vererbung

Vererbung heißt: Eine Klasse baut auf einer anderen auf und übernimmt deren Eigenschaften und Methoden. Die Grundklasse nennt man Elternklasse (parent), die neue Klasse Kindklasse. Das WARUM: Gemeinsames Verhalten wird einmal an einer Stelle geschrieben, statt es in jeder ähnlichen Klasse zu wiederholen. Weniger Wiederholung bedeutet weniger Fehlerquellen.

In Knospe siehst du das schön beim Plugin-System. Die Klasse `AbstractPlugin` in [`backend/src/Plugin/AbstractPlugin.php`](../../backend/src/Plugin/AbstractPlugin.php) liefert fertiges Grundverhalten, das jedes Plugin gebrauchen kann. Ein konkretes Plugin erbt davon und ergänzt nur seine Besonderheiten.

## extends

Mit dem Schlüsselwort `extends` sagst du, von wem geerbt wird.

```php
abstract class AbstractPlugin implements PluginInterface
{
    public function boot(): void
    {
        // Standard: nichts tun
    }
}

final class CommentsPlugin extends AbstractPlugin
{
    public function boot(): void
    {
        parent::boot();
        // eigene Routen und Hooks registrieren
    }
}
```

## Ueberschreiben und parent::

Die Kindklasse darf eine geerbte Methode neu definieren. Das nennt man Ueberschreiben. Wenn du dabei trotzdem noch die Version der Elternklasse ausführen willst, rufst du sie mit `parent::` auf - im Beispiel oben `parent::boot()`. So läuft erst das Grundverhalten und danach der eigene Zusatz.

## abstract

`AbstractPlugin` ist als `abstract` markiert. Eine abstrakte Klasse kann man nicht direkt instanzieren - sie ist bewusst nur als Bauplan für Kindklassen gedacht. So stellst du sicher, dass immer ein konkretes Plugin entsteht, nie die Rohform.

Womit `AbstractPlugin` sein Versprechen `implements PluginInterface` einlöst, erklärt [Interfaces und Traits](03-oop-interfaces-und-traits.md). Die Grundlagen von Klassen selbst findest du in [Klassen und Objekte](01-oop-klassen-intro.md).
