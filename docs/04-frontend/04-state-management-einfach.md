# Zustand teilen

Manche Daten braucht nicht nur eine Komponente, sondern die halbe Oberfläche. Der Anmeldezustand ist so ein Fall: die Kopfleiste zeigt den Namen, die Startseite blendet das Schreibformular ein, `PostItem.svelte` entscheidet, ob Bearbeiten-Knöpfe erscheinen. Würde jede Komponente diesen Zustand selbst laden, gäbe es Doppelarbeit und Widersprüche. Man braucht also einen gemeinsamen, reaktiven Ort.

## $state im .svelte.ts-Modul

Runes wie `$state` funktionieren nicht nur in `.svelte`-Dateien, sondern auch in normalen Modulen - sofern deren Name auf `.svelte.ts` endet. Genau das nutzt Knospe für den geteilten Zustand. In `frontend/src/lib/auth.svelte.ts`:

```ts
export const auth = $state<{ user: User | null; bereit: boolean }>({
  user: null,
  bereit: false,
});
```

`auth` ist ein reaktives Objekt. Jede Komponente, die `auth.user` liest, aktualisiert sich automatisch, sobald sich das Feld ändert. Kein Abonnieren, kein manuelles Benachrichtigen.

## Aendern über Funktionen

Damit der Zustand geordnet bleibt, wird er nicht wild von aussen überschrieben, sondern über Funktionen im selben Modul verändert:

```ts
export async function anmelden(email: string, password: string): Promise<void> {
  const { user } = await api.login(email, password);
  auth.user = user;
}

export async function abmelden(): Promise<void> {
  await api.logout();
  auth.user = null;
}
```

Eine Komponente importiert einfach, was sie braucht:

```svelte
import { auth, abmelden } from '../lib/auth.svelte';
```

und schreibt im Markup `{#if auth.user}` oder `{auth.user.displayName}` (so in `Nav.svelte`).

## Warum kein klassischer Store

Frühere Svelte-Versionen boten dafür eigene Store-Objekte mit `subscribe`. Mit den Runes aus Svelte 5 ist das nicht mehr nötig: ein exportiertes `$state`-Objekt ist bereits geteilter, reaktiver Zustand - schlicht und ohne Zusatzbegriffe. Denselben Ansatz nutzt auch der Router in `frontend/src/lib/router.svelte.ts` (siehe [Routing in der SPA](07-routing-sveltekit.md)).

Wie diese Daten überhaupt vom Backend kommen, steht in [API-Aufrufe mit fetch](03-api-aufrufe-fetch.md). Begriffe im [Glossar: Frontend](glossar-frontend.md).
