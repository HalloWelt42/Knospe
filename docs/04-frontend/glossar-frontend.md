# Glossar: Frontend

Kurze Erklärungen der wichtigsten Begriffe rund um die Oberfläche von Knospe. Ausführlicheres steht jeweils in den verlinkten Kapiteln.

## Komponente

Ein wiederverwendbarer Baustein der Oberfläche in einer `.svelte`-Datei: Markup, Logik und Stil zusammen. Beispiele liegen unter `frontend/src/components/`. Mehr in [Svelte-5-Komponenten](01-svelte5-komponenten-intro.md).

## Rune

Eine Sonderfunktion mit `$`-Präfix, mit der Svelte 5 Reaktivität steuert. `$state` markiert einen Wert als reaktiv, `$derived` berechnet abgeleitete Werte, `$props` entpackt hereingegebene Werte, `$effect` reagiert auf Aenderungen. Siehe [Svelte-5-Komponenten](01-svelte5-komponenten-intro.md) und den Fallstrick in [API-Aufrufe mit fetch](03-api-aufrufe-fetch.md).

## Prop

Ein Wert, den eine Komponente von aussen (von ihrer Elternkomponente) bekommt. In Svelte 5 über `$props()` entpackt und in TypeScript typisiert, etwa `let { post }: { post: Post } = $props()` in `PostItem.svelte`. Mehr in [TypeScript in Svelte](02-typescript-in-svelte.md).

## SPA

Single Page Application. Der Browser lädt einmal eine Seite; danach tauscht JavaScript die Ansichten aus, ohne Neuladen. Knospe steuert die Ansichten über einen kleinen Hash-Router in `frontend/src/lib/router.svelte.ts`. Mehr in [Routing in der SPA](07-routing-sveltekit.md).

## Proxy

Eine Weiterleitung im Dev-Server: Vite reicht alle Anfragen an `/api` an das PHP-Backend weiter, damit für den Browser alles wie derselbe Ursprung aussieht (kein CORS, Cookie reist mit). Konfiguriert in `frontend/vite.config.ts`. Mehr in [Vite und Werkzeuge](05-vite-und-tooling.md).

## Slot

Eine benannte Stelle in der Oberfläche, an die Plugins eigene Komponenten hängen können. `PluginSlot.svelte` rendert alle für einen Slot registrierten Bausteine, die die Registry in `frontend/src/lib/plugins.ts` per `import.meta.glob` einsammelt. Mehr im [Plugin-System](../05-plugins/01-plugin-system-konzept.md).

## Weiteres

- CSRF-Token, `fetch`, `async`/`await`: [API-Aufrufe mit fetch](03-api-aufrufe-fetch.md)
- Geteilter Zustand ohne klassischen Store: [Zustand teilen](04-state-management-einfach.md)
- Feldfehler und Formulare: [Formulare und Validierung](08-forms-und-validierung.md)
- CSS-Variablen und komponenteneigene Stile: [Styling](06-styling-css.md)
