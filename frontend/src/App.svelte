<script lang="ts">
  // Erste Seite: prueft die Verbindung zum PHP-Backend.
  // Svelte 5 Runes: $state haelt reaktiven Zustand.
  // Bewusst KEIN Fetch in einem $effect mit Ladeflag (das kann eine
  // Endlosschleife ausloesen), sondern ein einfacher Aufruf beim Laden.
  // Lern mehr: docs/04-frontend/03-api-aufrufe-fetch.md

  type Health = {
    status: string;
    php: string;
    zeit: string;
    datenbank: { verbunden: boolean; fehler: string | null };
  };

  let health = $state<Health | null>(null);
  let fehler = $state<string | null>(null);
  let laeuft = $state(false);

  async function pruefen() {
    laeuft = true;
    fehler = null;
    try {
      const res = await fetch('/api/health');
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      health = (await res.json()) as Health;
    } catch (e) {
      fehler = e instanceof Error ? e.message : 'Unbekannter Fehler';
      health = null;
    } finally {
      laeuft = false;
    }
  }

  // Einmal beim Laden pruefen.
  pruefen();
</script>

<main>
  <h1>Knospe</h1>
  <p class="unter">Lehr-Boilerplate - PHP 8.5, Svelte 5, PostgreSQL</p>

  <section class="karte">
    <h2><i class="fa-solid fa-heart-pulse"></i> Verbindungstest</h2>

    {#if laeuft && !health}
      <p>Pruefe Verbindung ...</p>
    {:else if fehler}
      <p class="fehler">Backend nicht erreichbar: {fehler}</p>
    {:else if health}
      <ul>
        <li>Status: <strong>{health.status}</strong></li>
        <li>PHP-Version: <strong>{health.php}</strong></li>
        <li>
          Datenbank:
          <strong class:ok={health.datenbank.verbunden} class:no={!health.datenbank.verbunden}>
            {health.datenbank.verbunden ? 'verbunden' : 'nicht verbunden'}
          </strong>
          {#if health.datenbank.fehler}
            <span class="detail">({health.datenbank.fehler})</span>
          {/if}
        </li>
      </ul>
    {/if}

    <button onclick={pruefen} disabled={laeuft}>
      {laeuft ? 'Pruefe ...' : 'Erneut pruefen'}
    </button>
  </section>

  <p class="hinweis">
    Naechster Schritt: der echte Kern (Router, Container, Pipeline) im
    Meilenstein M1. Siehe <code>docs/</code>.
  </p>
</main>

<style>
  :global(body) {
    margin: 0;
    font-family: 'Chakra Petch', system-ui, sans-serif;
    background: #0f1115;
    color: #e6e8eb;
  }

  main {
    max-width: 640px;
    margin: 0 auto;
    padding: 3rem 1.5rem;
  }

  h1 {
    margin: 0;
    font-size: 2.5rem;
    letter-spacing: 0.02em;
  }

  .unter {
    color: #8b93a1;
    margin-top: 0.25rem;
  }

  .karte {
    margin-top: 2rem;
    padding: 1.5rem;
    background: #171a21;
    border-radius: 12px;
  }

  h2 {
    margin-top: 0;
    font-size: 1.2rem;
  }

  ul {
    list-style: none;
    padding: 0;
    line-height: 2;
  }

  .ok {
    color: #4ade80;
  }

  .no {
    color: #f87171;
  }

  .fehler {
    color: #f87171;
  }

  .detail {
    color: #8b93a1;
    font-size: 0.85rem;
  }

  button {
    margin-top: 1rem;
    padding: 0.6rem 1.1rem;
    border: none;
    border-radius: 8px;
    background: #3b82f6;
    color: white;
    font: inherit;
    cursor: pointer;
  }

  button:hover:not(:disabled) {
    background: #2f6fe0;
  }

  button:disabled {
    opacity: 0.6;
    cursor: default;
  }

  .hinweis {
    margin-top: 2rem;
    color: #8b93a1;
    font-size: 0.9rem;
  }

  code {
    background: #222634;
    padding: 0.1rem 0.35rem;
    border-radius: 4px;
  }
</style>
