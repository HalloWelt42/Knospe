<script lang="ts">
  // Verwaltung der Plugins: auflisten, aktivieren, deaktivieren.
  // So kann der Nutzer alles in der Oberfläche erledigen (kein Skript nötig).
  // Lern mehr: docs/05-plugins/01-plugin-system-konzept.md
  import { onMount } from 'svelte';
  import { api, ApiError } from '../lib/api';
  import type { PluginInfo } from '../lib/types';
  import { auth } from '../lib/auth.svelte';

  let plugins = $state<PluginInfo[]>([]);
  let laden = $state(true);
  let fehler = $state<string | null>(null);
  let arbeitet = $state<string | null>(null);

  onMount(ladePlugins);

  async function ladePlugins() {
    laden = true;
    fehler = null;
    try {
      const { plugins: liste } = await api.listPlugins();
      plugins = liste;
    } catch (e) {
      fehler = e instanceof Error ? e.message : 'Fehler beim Laden';
    } finally {
      laden = false;
    }
  }

  async function umschalten(p: PluginInfo) {
    arbeitet = p.id;
    fehler = null;
    try {
      if (p.enabled) {
        await api.disablePlugin(p.id);
      } else {
        await api.enablePlugin(p.id);
      }
      await ladePlugins();
    } catch (e) {
      fehler = e instanceof ApiError ? e.message : 'Aktion fehlgeschlagen';
    } finally {
      arbeitet = null;
    }
  }
</script>

<h1>Plugins</h1>

{#if !auth.user}
  <p class="muted">Bitte zuerst <a href="/login">anmelden</a>.</p>
{:else if laden}
  <p class="muted">Lädt ...</p>
{:else if fehler}
  <p class="err">{fehler}</p>
{:else if plugins.length === 0}
  <p class="muted">
    Noch keine Plugins gefunden. Lege eines unter <code>plugins/</code> an -
    siehe <code>docs/05-plugins/</code>.
  </p>
{:else}
  <ul class="liste">
    {#each plugins as p (p.id)}
      <li class="karte">
        <div class="kopf">
          <div>
            <h3>{p.name} <span class="version">v{p.version}</span></h3>
            <p class="caps">{p.capabilities.join(', ') || 'keine Angaben'}</p>
          </div>
          <span class="status" class:an={p.enabled} class:aus={!p.enabled}>
            {p.enabled ? 'aktiv' : 'inaktiv'}
          </span>
        </div>
        <button
          class={p.enabled ? 'btn-danger' : 'btn'}
          onclick={() => umschalten(p)}
          disabled={arbeitet === p.id}
        >
          {#if arbeitet === p.id}
            Moment ...
          {:else}
            {p.enabled ? 'Deaktivieren' : 'Aktivieren'}
          {/if}
        </button>
      </li>
    {/each}
  </ul>
  <p class="muted hinweis">
    Beim Aktivieren laufen die Migrationen des Plugins automatisch. Für
    beigesteuerte Oberflächen-Teile aktualisiert der Vite-Entwicklungsserver
    von selbst.
  </p>
{/if}

<style>
  .liste {
    list-style: none;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .kopf {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.8rem;
  }

  h3 {
    margin: 0;
    font-size: 1.05rem;
  }

  .version {
    color: var(--muted);
    font-size: 0.8rem;
    font-weight: 400;
  }

  .caps {
    margin: 0.2rem 0 0;
    color: var(--muted);
    font-size: 0.82rem;
  }

  .status {
    font-size: 0.8rem;
    padding: 0.15rem 0.55rem;
    border-radius: 999px;
    border: 1px solid var(--border);
    white-space: nowrap;
  }

  .status.an {
    color: var(--ok);
  }

  .status.aus {
    color: var(--muted);
  }

  .hinweis {
    margin-top: 1.2rem;
    font-size: 0.85rem;
  }

  code {
    background: var(--panel-2);
    padding: 0.1rem 0.35rem;
    border-radius: 4px;
  }
</style>
