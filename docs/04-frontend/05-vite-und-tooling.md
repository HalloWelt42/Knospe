# Vite und Werkzeuge

Der Browser versteht keine `.svelte`-Dateien und kein TypeScript. Etwas muss diese Quellen also in JavaScript und CSS übersetzen, das der Browser lädt. Diese Aufgabe übernimmt Vite - der Build- und Entwicklungsserver des Frontends. Die Konfiguration steht in [`frontend/vite.config.ts`](../../frontend/vite.config.ts), die Befehle in [`frontend/package.json`](../../frontend/package.json).

## Der Dev-Server und HMR

Während der Entwicklung startet man den Server mit:

```bash
npm run dev
```

Er liefert die Oberfläche aus und beobachtet die Dateien. Ändert man eine Komponente, tauscht Vite nur diesen Baustein im laufenden Browser aus, ohne die ganze Seite neu zu laden - das nennt sich Hot Module Replacement (HMR). Der Zustand der Seite bleibt dabei meist erhalten, was das Ausprobieren sehr schnell macht.

## Proxy für /api

Die Oberfläche läuft im Dev-Betrieb auf einem anderen Port als das PHP-Backend. Damit `fetch('/api/...')` (siehe [API-Aufrufe mit fetch](03-api-aufrufe-fetch.md)) trotzdem am Backend landet - ohne CORS-Aerger -, leitet Vite alle `/api`-Anfragen weiter:

```ts
proxy: {
  '/api': {
    target: process.env.API_PROXY_TARGET || 'http://localhost:8000',
    changeOrigin: true,
  },
}
```

Für den Browser sieht so alles wie derselbe Ursprung aus. Das ist wichtig, weil das Sitzungs-Cookie nur bei same-origin automatisch mitgeschickt wird.

## fs.allow für Plugins

Plugin-Frontends liegen außerhalb der Frontend-Wurzel, nämlich unter `plugins/<id>/frontend/`. Damit Vite dort lesen darf, ist der Zugriff freigegeben:

```ts
fs: {
  allow: ['..'],
}
```

So kann die Registry in [`frontend/src/lib/plugins.ts`](../../frontend/src/lib/plugins.ts) per `import.meta.glob` die `*.plugin.ts`-Dateien der Plugins einsammeln. Details dazu im [Plugin-System](../05-plugins/01-plugin-system-konzept.md).

## Bauen für den Betrieb

Für die Auslieferung erzeugt man statische Dateien:

```bash
npm run build
```

Das Ergebnis ist fertiges HTML, JavaScript und CSS - ohne Vite, ohne TypeScript-Werkzeug. Zusätzlich prüft `npm run check` die Typen der ganzen Oberfläche. Begriffe wie Proxy und SPA erklärt das [Glossar: Frontend](glossar-frontend.md).
