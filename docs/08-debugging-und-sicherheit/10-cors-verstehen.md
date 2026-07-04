# CORS verstehen

CORS steht für Cross-Origin Resource Sharing. Es klingt kompliziert, ist aber eine einzige Idee: Der Browser erlaubt einer Webseite standardmäßig nur Anfragen an dieselbe Herkunft (Origin), von der sie geladen wurde. Eine Origin ist die Kombination aus Schema, Host und Port, also zum Beispiel http://localhost:5173. Ruft JavaScript von dort etwas unter einer anderen Origin ab, greift die Same-Origin-Regel und der Browser blockiert die Antwort - es sei denn, der Server erlaubt die fremde Herkunft ausdrücklich per CORS-Header.

## Warum unser Dev-Proxy CORS vermeidet

Im Frontend läuft Vite auf einem eigenen Port, das Backend auf einem anderen. Das wären zwei Origins - CORS-Fall. Statt CORS zu öffnen, nutzt Knospe einen Dev-Proxy: Vite leitet alle Anfragen an `/api` intern an den PHP-Container weiter. Aus Sicht des Browsers geht damit alles an dieselbe Origin. Kein CORS nötig, keine Sonderregeln, und die Cookies reisen ganz natürlich mit.

```ts
// frontend/vite.config.ts (Kern)
server: {
  proxy: { '/api': { target: 'http://php:8080', changeOrigin: true } }
}
```

## Wann CORS wirklich nötig wird

Sobald Frontend und Backend produktiv unter verschiedenen Domains liegen und man keinen Proxy davorstellt, muss der Server die erlaubte Herkunft benennen. Dann gilt eine harte Regel.

## Whitelist statt Sternchen mit Credentials

Der Wert `Access-Control-Allow-Origin: *` erlaubt jede Herkunft - das ist genau dann verboten, wenn Anmeldedaten (Cookies) im Spiel sind. Der Browser schickt bei `*` keine Credentials mit. Richtig ist eine Whitelist konkreter, erlaubter Origins plus `Access-Control-Allow-Credentials: true`.

```
Access-Control-Allow-Origin: https://app.beispiel.de
Access-Control-Allow-Credentials: true
```

## Bezug zum CSRF-Schutz

CORS und CSRF werden gern verwechselt. CORS ist eine Browser-Regel, die Antworten fremder Origins verbirgt - sie schützt nicht davor, dass eine fremde Seite eine verändernde Anfrage abschickt. Genau dafür ist unser Token-Schutz in `src/Http/Middleware/CsrfMiddleware.php` da: Bei POST/PUT/PATCH/DELETE muss der Header `X-CSRF-Token` stimmen. Erst beide zusammen ergeben ein sauberes Bild, siehe [Sicherheits-Grundlagen](07-sicherheits-basics.md).

## Faustregeln

- In Entwicklung lieber Proxy als offene CORS-Header.
- Niemals `*` zusammen mit Cookies.
- CORS ersetzt keinen CSRF-Schutz.
