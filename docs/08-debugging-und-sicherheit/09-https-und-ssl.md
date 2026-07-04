# HTTPS und TLS

Wenn Daten zwischen Browser und Server über HTTP reisen, liegen sie offen: Jeder Knoten dazwischen (WLAN, Provider, Zwischenrechner) kann mitlesen und sogar verändern. Für eine App mit Login und Sitzungs-Cookies ist das untragbar. HTTPS ist HTTP durch einen verschlüsselten Kanal - technisch sorgt dafür TLS (Transport Layer Security).

## Was TLS leistet

- Vertraulichkeit: Der Inhalt ist unterwegs verschlüsselt.
- Integrität: Manipulation fällt auf.
- Authentizität: Ein Zertifikat belegt, dass der Server wirklich der ist, für den er sich ausgibt.

Der letzte Punkt ist der Grund für Zertifikate. Ein Zertifikat bindet einen Domainnamen an einen öffentlichen Schlüssel und ist von einer Zertifizierungsstelle signiert, der Browser vertrauen.

## Lokal: selbstsigniert

In der Entwicklung reicht oft einfaches HTTP über localhost, denn der Verkehr verlässt den eigenen Rechner nicht. Braucht man lokal doch HTTPS (etwa um Cookie-Flags wie `Secure` realistisch zu testen), erzeugt man ein selbstsigniertes Zertifikat. Der Browser warnt dann, weil niemand ausser einem selbst dafür bürgt - lokal ist das in Ordnung, man fügt das eigene Zertifikat einmalig als vertrauenswürdig hinzu.

## Produktion: echte Zertifikate

Auf einem echten Server nimmt man ein Zertifikat einer anerkannten Stelle. Kostenlose, automatisch erneuerbare Zertifikate sind heute Standard. Praktisch endet TLS meist nicht in PHP selbst, sondern in einem vorgelagerten Reverse-Proxy oder Webserver, der entschlüsselt und die Anfrage intern per HTTP an unseren PHP-Container weiterreicht (TLS-Terminierung).

## Bezug zu Cookies

Erst mit HTTPS ergibt das Cookie-Flag `Secure` Sinn: Damit wird das Sitzungs-Cookie nur noch über verschlüsselte Verbindungen gesendet. Zusammen mit `HttpOnly` und `SameSite=Lax` (siehe `src/Support/Session.php`) ist die Sitzung dann gut geschützt.

## Faustregeln

- In Produktion niemals ohne HTTPS. Sitzungs-Cookies gehören verschlüsselt.
- Selbstsignierte Zertifikate nur lokal, nie öffentlich.
- Zertifikate automatisch erneuern lassen, damit sie nicht ablaufen.

Weiter geht es mit dem Feld, in dem Browser besonders streng werden: [CORS verstehen](10-cors-verstehen.md).
