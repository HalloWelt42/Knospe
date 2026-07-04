# Routing in der SPA

Knospe ist eine Single Page Application (SPA): der Browser lädt einmal eine Seite, danach tauscht JavaScript die Ansichten aus, ohne neu zu laden. Damit trotzdem verschiedene Bildschirme erreichbar und verlinkbar sind, braucht es Routing - eine Zuordnung von Adresse zu Ansicht. Knospe bringt dafür bewusst nur einen winzigen eigenen Router mit, keinen großen Router und kein serverseitiges Routing. Für ein Boilerplate zum Verstehen ist das die richtige Größe: man sieht in wenigen Zeilen, wie Routing wirklich funktioniert.

## Ein Hash-Router

Der Router in `frontend/src/lib/router.svelte.ts` liest den Teil der Adresse hinter dem `#`, das URL-Fragment. Das Fragment ändert die Seite nicht neu, eignet sich also perfekt für eine SPA. Aus dem Fragment leitet `parse()` eine Route ab:

```ts
export interface Route {
  name: 'home' | 'login' | 'register' | 'post' | 'plugins';
  id?: number;
}

function parse(): Route {
  const hash = location.hash.replace(/^#/, '') || '/';
  if (hash === '/login') return { name: 'login' };
  const match = hash.match(/^\/posts\/(\d+)$/);
  if (match) return { name: 'post', id: Number(match[1]) };
  return { name: 'home' };
}
```

Die aktuelle Route liegt als reaktiver Zustand vor (`export const route = $state<Route>(parse())`) und wird bei jedem `hashchange` aktualisiert - derselbe Ansatz wie beim geteilten Zustand in [Zustand teilen](04-state-management-einfach.md).

## Ansichten wählen

`src/App.svelte` liest `route` und zeigt die passende Komponente:

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

## Navigieren

Wechseln geht über `navigate()`:

```ts
export function navigate(path: string): void {
  location.hash = path;
}
```

Nach erfolgreicher Anmeldung ruft `LoginView.svelte` einfach `navigate('/')`. Normale Links im Markup nutzen `href="#/login"`. Begriffe wie SPA im [Glossar: Frontend](glossar-frontend.md).
