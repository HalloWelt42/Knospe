# PHP-CS-Fixer nutzen

Einheitlich formatierter Code ist leichter zu lesen und erzeugt saubere Aenderungen in Git. Damit nicht jede Person eigene Einrückungen und Klammerstile pflegt, legt PHP-CS-Fixer eine feste Norm fest und stellt Code automatisch darauf um. Knospe folgt dem verbreiteten Standard PSR-12.

## Die Konfiguration

Die Regeln stehen in der Datei `.php-cs-fixer.dist.php` im Backend. Sie legt fest, welche Ordner geprüft werden (der Quellcode unter `backend/src` und die Tests) und dass PSR-12 gilt. Diese Datei ist Teil des Repositorys, damit alle dieselbe Norm nutzen.

## Auf der Kommandozeile

Am einfachsten läufst du den Fixer über die Composer-Skripte:

```
composer lint
```

Das prüft nur und meldet, welche Dateien nicht der Norm entsprechen - es ändert nichts. Willst du die Dateien tatsächlich korrigieren lassen:

```
composer lint:fix
```

Danach sind alle geprüften Dateien sauber formatiert. Führe `composer lint:fix` am besten vor jedem Commit aus, dann bleibt die Historie ordentlich.

## Aus PhpStorm heraus

Du kannst den Fixer auch in der IDE hinterlegen: Unter "Settings > PHP > Quality Tools > PHP CS Fixer" gibst du den Pfad zum Fixer (im Container unter `/app/backend/vendor/bin/php-cs-fixer`) und die Konfigurationsdatei an. Dann markiert PhpStorm Abweichungen direkt im Editor, und du kannst sie per Alt+Enter beheben.

## Format bei Bedarf, nicht dauernd

Formatiere bewusst, typischerweise vor dem Commit oder wenn eine Datei unruhig aussieht. So trennst du inhaltliche Aenderungen von reinen Formatierungen und die Reviews bleiben verständlich. Wie du die eigentlichen Warnungen zum Codeverhalten liest, steht in [Code-Inspektion](07-phpstorm-code-inspection.md).
