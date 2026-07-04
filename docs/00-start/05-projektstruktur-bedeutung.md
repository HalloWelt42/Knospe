# Projektstruktur verstehen

Die [Datei-Übersicht](04-datei-uebersicht.md) zeigt, was wo liegt. Hier geht es um das Warum: welche Ordnung dahinter steckt und wie du die richtige Datei findest, wenn du etwas ändern willst.

## PSR-4: Namensraum trifft Ordner

Das Backend nutzt PSR-4-Autoloading. Das ist eine Vereinbarung, die einen Namensraum-Präfix fest einem Ordner zuordnet. Bei Knospe gilt:

```
Knospe\  ->  backend/src
```

Die Klasse `Knospe\Domain\Post\PostService` liegt also zwingend in `backend/src/Domain/Post/PostService.php`. Diese Symmetrie ist kein Zufall: Sie erlaubt es, jede Klasse allein am Namen im Dateibaum zu finden, ohne zu suchen. Composer lädt sie dann automatisch, sobald sie gebraucht wird.

## Wo finde ich was?

Ein Beispiel. Du willst einen neuen API-Endpunkt bauen, sagen wir `GET /api/posts/latest`. Dann brauchst du zwei Dinge:

1. Einen Handler, der die Anfrage beantwortet. Handler liegen unter `backend/src/Http/Handler/`, thematisch gruppiert (etwa im Ordner `Post`). Ein Handler ist eine kleine Klasse mit einer Methode, die eine Anfrage entgegennimmt und eine JSON-Antwort zurückgibt.
2. Einen Eintrag in `backend/config/routes.php`, der die Adresse `/api/posts/latest` mit deinem Handler verbindet. Der Router aus `backend/src/Core/` liest diese Tabelle und findet zur Anfrage den passenden Handler.

Merksatz: Ein Endpunkt ist immer ein Handler plus eine Zeile in der Routen-Tabelle. Mehr Magie gibt es nicht.

## Symmetrie Frontend und Backend

Das Frontend spiegelt diese Ordnung. Datenmodelle wie ein Beitrag stehen im Backend als readonly-Entity in `backend/src/Domain/Post/`, im Frontend als TypeScript-Typ in `frontend/src/lib/types.ts`. Der API-Client `frontend/src/lib/api.ts` ruft genau die Endpunkte auf, die das Backend anbietet. So findest du zu jeder Backend-Route die passende Frontend-Stelle - und umgekehrt.

## Trennung nach Verantwortung

Der Kern (`Core`) weiß nichts über Beiträge oder Nutzer; er kümmert sich nur um den Ablauf einer Anfrage. Die Fachlogik (`Domain`) weiß nichts über HTTP; sie arbeitet mit reinen Objekten. Diese Trennung macht jeden Teil einzeln verständlich und austauschbar.

## Weiter

Wie eine Anfrage konkret durch den Kern läuft, vertieft der Themenblock `02-architektur/`.
