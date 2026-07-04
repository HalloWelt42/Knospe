# API-Aufrufe mit fetch

Die Oberfläche holt sich ihre Daten vom PHP-Backend über HTTP. Das Werkzeug im Browser dafür ist `fetch`. Statt aber an vielen Stellen einzeln `fetch` zu rufen, bündelt Knospe alle Aufrufe in einem typisierten Client: `frontend/src/lib/api.ts`. Warum? So steht die Fehlerbehandlung, das CSRF-Handling und die Rückgabetypisierung an genau einer Stelle - jede Ansicht ruft nur noch `api.listPosts()` oder `api.login(...)`.

## async und await

Ein Netzwerkaufruf dauert. `await` wartet, ohne die Oberfläche zu blockieren; die Funktion drumherum ist dafür `async`. So lädt `PostsView.svelte` seine Liste:

```ts
async function ladePosts() {
  laden = true;
  try {
    const { posts: geladen } = await api.listPosts();
    posts = geladen;
  } catch (e) {
    ladefehler = e instanceof Error ? e.message : 'Fehler beim Laden';
  } finally {
    laden = false;
  }
}
```

## Fehler über response.ok

`fetch` wirft nicht von selbst bei einem Fehlerstatus wie 404 oder 422. Deshalb prüft der Client in `api.ts` `response.ok` selbst und wandelt Fehler in ein `ApiError` mit Meldung und Feldfehlern um:

```ts
if (!res.ok) {
  const message = (data && data.meldung) || `Fehler ${res.status}`;
  throw new ApiError(message, res.status, (data && data.felder) || {});
}
```

So kann jede Ansicht mit `catch (e)` sauber reagieren und die `felder` als Feldfehler anzeigen (siehe [Formulare und Validierung](08-forms-und-validierung.md)).

## CSRF-Handling

Bei verändernden Anfragen (POST, PUT, DELETE) verlangt das Backend ein CSRF-Token als Header `X-CSRF-Token`. Der Client holt es einmal über `GET /api/csrf`, merkt es sich und setzt es automatisch:

```ts
if (method !== 'GET' && method !== 'HEAD') {
  headers['X-CSRF-Token'] = await ensureCsrf();
}
```

Die Sitzung selbst reist im Cookie mit, weil `credentials: 'same-origin'` gesetzt ist.

## Fallstrick: fetch im $effect

Ein `$effect` läuft erneut, sobald sich ein darin gelesener Wert ändert. Setzt der Effekt ein Ladeflag, das er selbst liest, entsteht eine Endlosschleife. Deshalb: Daten nicht im `$effect` laden. In Knospe passiert das Laden über `onMount(ladePosts)` (einmal beim Einhängen); beim Wechsel eines Beitrags erzwingt `{#key route.id}` in `App.svelte` ein sauberes Neuladen. Mehr im [Glossar: Frontend](glossar-frontend.md).
