# Svelte-5-Komponenten

Eine Komponente ist ein wiederverwendbarer Baustein der Oberfläche: ein Stück Markup, seine Logik und sein Aussehen in einer Datei. In Knospe liegen alle Komponenten unter `frontend/src/components/` und tragen die Endung `.svelte`. Warum eine eigene Dateiendung? Weil eine `.svelte`-Datei kein reines HTML ist: der Vite-Build kompiliert sie zu schlankem JavaScript, das genau die Teile der Seite aktualisiert, die sich geändert haben.

## Aufbau einer .svelte-Datei

Jede Komponente hat bis zu drei Bereiche:

```svelte
<script lang="ts">
  let anzahl = $state(0);
</script>

<button onclick={() => anzahl++}>Geklickt: {anzahl}</button>

<style>
  button { font: inherit; }
</style>
```

- `<script lang="ts">` hält die Logik (siehe [TypeScript in Svelte](02-typescript-in-svelte.md)).
- Der mittlere Teil ist das Markup. In `{...}` stehen Ausdrücke; `{anzahl}` zeigt den aktuellen Wert.
- `<style>` ist auf diese Komponente begrenzt (siehe [Styling](06-styling-css.md)).

## Runes: $state und $props

Svelte 5 steuert Reaktivität über sogenannte Runes, kleine Sonderfunktionen mit `$`-Präfix. `$state(...)` markiert einen Wert als reaktiv: ändert er sich, rendert die Komponente die betroffenen Stellen neu. In `PostsView.svelte` ist das etwa `let posts = $state<Post[]>([])`.

Werte, die eine Komponente von außen bekommt, heißen Props. Sie werden mit `$props()` entpackt. So nimmt `PostItem.svelte` seinen Beitrag entgegen:

```svelte
let { post, onDeleted }: { post: Post; onDeleted: (id: number) => void } = $props();
```

## Wie alles startet: mount()

Es gibt eine Wurzelkomponente, `src/App.svelte`. Sie wird in `src/main.ts` mit `mount()` in die Seite eingehängt:

```ts
import { mount } from 'svelte';
import App from './App.svelte';

const app = mount(App, { target: document.getElementById('app')! });
```

`App.svelte` wählt dann anhand der aktuellen Route (siehe [Routing in der SPA](07-routing-sveltekit.md)) die passende Ansicht aus. Ab hier baut sich die gesamte Oberfläche aus verschachtelten Komponenten auf. Begriffe wie Rune und Prop findest du im [Glossar: Frontend](glossar-frontend.md).
