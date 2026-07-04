# TypeScript in Svelte

TypeScript ist JavaScript mit Typen. Man schreibt hin, welche Form Daten haben - eine Zahl, ein Text, ein Objekt mit bestimmten Feldern -, und der Editor sowie der Build prüfen, ob man sich daran hält. Der Nutzen: Tippfehler und falsche Zugriffe fallen sofort auf, nicht erst zur Laufzeit im Browser. In Knospe ist das durchgängig aktiviert.

## lang="ts"

Damit eine Komponente TypeScript versteht, trägt ihr Skript-Block das Attribut `lang="ts"`:

```svelte
<script lang="ts">
  let email = $state('');
</script>
```

Ohne dieses Attribut wäre der Block normales JavaScript. Alle Komponenten in `frontend/src/components/` nutzen `lang="ts"`.

## Interfaces als gemeinsame Datentypen

Die Datenstrukturen, die vom PHP-Backend kommen, sind einmal zentral beschrieben - in [`frontend/src/lib/types.ts`](../../frontend/src/lib/types.ts). Ein Interface ist eine Beschreibung der Form eines Objekts:

```ts
export interface Post {
  id: number;
  authorId: number;
  authorName: string | null;
  title: string;
  body: string;
  createdAt: string;
}
```

`authorName: string | null` sagt: der Name ist ein Text oder fehlt. So muss man im Markup daran denken, den Fall abzudecken, etwa mit `{post.authorName ?? 'Unbekannt'}`.

## Props typisieren

Props einer Komponente werden direkt beim `$props()`-Aufruf typisiert. Aus `PostItem.svelte`:

```svelte
let {
  post,
  onDeleted,
  onUpdated,
}: {
  post: Post;
  onDeleted: (id: number) => void;
  onUpdated: (post: Post) => void;
} = $props();
```

Damit weiß die Komponente genau, dass `post` ein `Post` ist, und dass `onDeleted` eine Funktion ist, die eine Nummer entgegennimmt. Uebergibt die Elternkomponente etwas Falsches, meldet der Build es.

## Der Nutzen im Alltag

Weil `api.listPosts()` (siehe [API-Aufrufe mit fetch](03-api-aufrufe-fetch.md)) ein `{ posts: Post[] }` zurückgibt, kennt der Editor beim Tippen von `post.` sofort alle erlaubten Felder. Das reduziert Nachschlagen und Fehler. Prüfen kann man alles jederzeit mit `npm run check`. Begriffe im [Glossar: Frontend](glossar-frontend.md).
