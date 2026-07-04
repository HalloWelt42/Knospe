# Spickzettel: Svelte 5 + TypeScript

Das Frontend von Knospe ist eine Svelte-5-SPA mit TypeScript und den neuen Runes. Runes machen sichtbar, was reaktiv ist. Dieser Spickzettel deckt die Muster aus `frontend/src` ab.

## Runes

| Rune | Zweck |
| --- | --- |
| `$state` | veränderlicher, reaktiver Wert |
| `$derived` | abgeleiteter Wert, folgt automatisch |
| `$effect` | Nebenwirkung bei Änderung |
| `$props` | Eingaben einer Komponente |

```svelte
<script lang="ts">
  let count = $state(0);
  let doubled = $derived(count * 2);
  let { title }: { title: string } = $props();
</script>
```

## Events

Svelte 5 nutzt normale Attribute statt einer eigenen Direktive:

```svelte
<button onclick={() => count++}>Plus</button>
<form onsubmit={handleSubmit}>...</form>
```

## Kontrollblöcke

```svelte
{#if user}
  <p>Hallo {user.email}</p>
{:else}
  <p>Bitte anmelden</p>
{/if}

{#each posts as post (post.id)}
  <PostItem {post} />
{/each}
```

`{#key wert}...{/key}` baut einen Block neu auf, wenn sich `wert` ändert. Das ist der saubere Weg, um beim Wechsel neu zu laden.

## Wichtige Warnung: kein Fetch im $effect

Ein `$effect`, der ein Ladeflag liest und schreibt und dabei lädt, ruft sich endlos selbst auf. Lade stattdessen auf Top-Level oder erzwinge ein Neu-Aufbauen per `{#key}`.

```svelte
// falsch: Endlosschleife
$effect(() => { if (!loaded) { load(); loaded = true; } });
// richtig: Top-Level-Aufruf, Remount per {#key}
```

## App starten

Die SPA hängt sich per `mount()` an das DOM (`frontend/src/main.ts`):

```ts
import { mount } from 'svelte';
mount(App, { target: document.getElementById('app')! });
```

Vertiefung: [Reaktivität mit Runes](../../04-frontend/01-svelte5-komponenten-intro.md) und [Router ohne Framework](../../04-frontend/07-routing-sveltekit.md). Referenz: svelte.dev.
