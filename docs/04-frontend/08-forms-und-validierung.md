# Formulare und Validierung

Über Formulare gelangen Eingaben in die Anwendung: anmelden, registrieren, einen Beitrag schreiben. Zwei Fragen stellen sich immer: Wie schickt man die Eingaben ab? Und wie zeigt man dem Nutzer, wenn etwas nicht stimmt? Wichtig zu verstehen: Prüfungen im Browser sind bequem, aber niemals verlässlich - die letzte Instanz ist das Backend.

## Absenden mit onsubmit

Ein Formular reagiert auf sein Absenden über `onsubmit`. Wir verhindern das Neuladen der Seite mit `e.preventDefault()` und rufen stattdessen den API-Client. Aus `LoginView.svelte`:

```svelte
<form class="karte" onsubmit={absenden}>
  ...
</form>
```

```ts
async function absenden(e: Event) {
  e.preventDefault();
  sendet = true;
  fehler = null;
  try {
    await anmelden(email, password);
    navigate('/');
  } catch (e) {
    fehler = e instanceof ApiError ? e.message : 'Anmeldung fehlgeschlagen';
  } finally {
    sendet = false;
  }
}
```

Die Eingaben hängen über `bind:value` an reaktivem `$state` (etwa `let email = $state('')`), sodass Feld und Variable stets denselben Wert haben.

## Clientseitig vs. serverseitig

Clientseitig kann man Grobes abfangen - Pflichtfelder, Mindestlänge, das `type="email"`-Attribut - für schnelle Rückmeldung. Doch der Browser lässt sich umgehen. Deshalb prüft das PHP-Backend jede Eingabe erneut und ist die eigentliche Autorität. Erst was das Backend akzeptiert, gilt.

## Feldfehler aus der API zeigen

Lehnt das Backend etwas ab, liefert es strukturierte Feldfehler. Der Client verpackt sie in ein `ApiError` mit einem `fields`-Objekt (siehe [API-Aufrufe mit fetch](03-api-aufrufe-fetch.md)). `RegisterView.svelte` liest sie aus und zeigt sie am jeweiligen Feld:

```ts
catch (e) {
  if (e instanceof ApiError) {
    felder = e.fields;
    fehler = e.message;
  }
}
```

Im Markup steht dann pro Feld:

```svelte
<input id="r-email" type="email" bind:value={email} />
{#if felder.email}<span class="fehler">{felder.email}</span>{/if}
```

So sieht der Nutzer genau, welches Feld gemeint ist. Dasselbe Muster nutzt das Formular zum Anlegen eines Beitrags in `PostsView.svelte`. Wie die Eingaben in reaktive Werte gebunden werden, steht in [Svelte-5-Komponenten](01-svelte5-komponenten-intro.md). Begriffe im [Glossar: Frontend](glossar-frontend.md).
