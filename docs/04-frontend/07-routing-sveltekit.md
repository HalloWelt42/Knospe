# Routing in der SPA

Knospe ist eine Single Page Application (SPA): der Browser lädt einmal eine Seite, danach tauscht JavaScript die Ansichten aus, ohne neu zu laden. Damit trotzdem verschiedene Bildschirme erreichbar und verlinkbar sind, braucht es Routing - eine Zuordnung von Adresse zu Ansicht. Knospe bringt dafür bewusst nur einen winzigen eigenen Router mit, keinen großen Router und kein serverseitiges Routing. Für ein Boilerplate zum Verstehen ist das die richtige Größe: man sieht in wenigen Zeilen, wie Routing wirklich funktioniert.

## Echte Pfade über die History-API

Der Router in [`frontend/src/lib/router.svelte.ts`](../../frontend/src/lib/router.svelte.ts) nutzt die History-API des Browsers. Dadurch sehen die Adressen sauber aus - `/register` statt `/#/register`. Aus dem Pfad (`location.pathname`) leitet `parse()` eine Route ab:

```ts
function parse(): Route {
  const path = location.pathname || '/';
  if (path === '/login') return { name: 'login' };
  const match = path.match(/^\/posts\/(\d+)$/);
  if (match) return { name: 'post', id: Number(match[1]) };
  return { name: 'home' };
}
```

Die aktuelle Route liegt als reaktiver Zustand vor (`export const route = $state<Route>(parse())`) - derselbe Ansatz wie beim geteilten Zustand in [Zustand teilen](04-state-management-einfach.md).

## Ansichten wählen

[`src/App.svelte`](../../frontend/src/App.svelte) liest `route` und zeigt die passende Komponente:

```svelte
{#if route.name === 'login'}
  <LoginView />
{:else if route.name === 'post' && route.id !== undefined}
  {#key route.id}
    <PostDetail id={route.id} />
  {/key}
{:else}
  <PostsView />
{/if}
```

Das `{#key route.id}` ist wichtig: wechselt man von Beitrag 3 zu Beitrag 7, baut Svelte `PostDetail` frisch auf und lädt sauber neu - das umgeht den Fetch-im-Effekt-Fallstrick aus [API-Aufrufe mit fetch](03-api-aufrufe-fetch.md).

## Navigieren und Links

Wechseln geht über `navigate()`. Es legt den neuen Pfad in der History ab und aktualisiert die Route, ohne die Seite neu zu laden:

```ts
export function navigate(path: string): void {
  if (path !== location.pathname) {
    history.pushState({}, '', path);
  }
  aktualisieren();
}
```

Damit gewöhnliche Links wie `href="/login"` nicht die ganze Seite neu laden, fängt `initRouter()` Klicks auf interne Links zentral ab und ruft `navigate()` auf. Der Zurück-Knopf des Browsers löst `popstate` aus und aktualisiert die Ansicht ebenfalls.

Wichtig bei echten Pfaden: Der Server muss unbekannte Pfade auf [`index.html`](../../frontend/index.html) zurückfallen lassen, damit ein direkter Aufruf von `/register` funktioniert. Der Vite-Server erledigt das im SPA-Modus von selbst; für den Produktivbetrieb steht der passende Hinweis in [Docker Compose im Detail](../06-deployment/01-docker-compose-detailliert.md).

Begriffe wie SPA im [Glossar: Frontend](glossar-frontend.md).
