# Styling

Das Aussehen der Oberfläche entsteht aus zwei Ebenen: globalen Regeln, die überall gelten, und komponenteneigenen Regeln, die nur eine Komponente betreffen. Diese Trennung verhindert, dass ein Stil aus einer Ecke unbeabsichtigt eine ganz andere Stelle verunstaltet.

## Komponenten-eigene Stile

Der `<style>`-Block in einer `.svelte`-Datei gilt nur für diese Komponente - Svelte grenzt die Regeln automatisch ab (scoped). Aus `Nav.svelte`:

```svelte
<style>
  .nav {
    display: flex;
    justify-content: space-between;
    padding: 0.9rem 1.5rem;
    background: var(--panel);
    border-bottom: 1px solid var(--border);
  }
</style>
```

Die Klasse `.nav` beeinflusst also keine andere `.nav` woanders. Das macht Komponenten unabhängig und gefahrlos änderbar.

## Globale Stile mit CSS-Variablen

Was überall gleich sein soll - Farben, Schrift, wiederkehrende Klassen wie `.btn`, `.karte`, `.field` -, steht in `frontend/src/app.css`. Farben liegen als CSS-Variablen im `:root`, sodass ein Wert an einer Stelle den ganzen Look ändert:

```css
:root {
  --bg: #0f1115;
  --panel: #171a21;
  --text: #e6e8eb;
  --accent: #3b82f6;
  --border: #262b36;
}
```

Komponenten greifen überall auf diese Variablen zu, etwa `background: var(--panel)`. Das hält das Bild einheitlich und macht Umfärben trivial. Geladen wird `app.css` einmal zentral in `frontend/src/main.ts`, zusammen mit der Schrift Chakra Petch und den Font-Awesome-Icons - beide als npm-Pakete, kein CDN.

## Akzente ohne Randstreifen

Eine bewusste Gestaltungsregel: Akzente werden über Flächen, volle Rahmen oder Farbe gesetzt, nicht über einen blossen Streifen links oder rechts an einem Element. Ein linker Farbbalken als Deko wirkt schnell unruhig und bricht bei schmalen Ansichten. Stattdessen nutzt Knospe abgerundete Karten (`.karte` mit `background` und `border-radius`) und einen dezenten Rahmen unten an der Kopfleiste.

Wie Stile zur fertigen CSS-Datei werden, übernimmt der Build (siehe [Vite und Werkzeuge](05-vite-und-tooling.md)). Zum Aufbau einer Komponente siehe [Svelte-5-Komponenten](01-svelte5-komponenten-intro.md). Begriffe im [Glossar: Frontend](glossar-frontend.md).
