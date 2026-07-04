<script lang="ts">
  // Kommentar-Ansicht unter einem Beitrag. Bewusst eigenstaendig gehalten
  // (eigene fetch-Aufrufe), damit das Plugin ohne Abhaengigkeit vom Kern-Code
  // der Oberflaeche funktioniert. Die globalen CSS-Klassen (karte, btn, ...)
  // stammen aus der App und sorgen fuer ein einheitliches Aussehen.
  // Lern mehr: docs/05-plugins/04-dein-erstes-plugin.md
  import { onMount } from 'svelte';

  interface Comment {
    id: number;
    post_id: number;
    author_id: number;
    content: string;
    created_at: string;
    author_name: string;
  }

  let { postId }: { postId: number } = $props();

  let comments = $state<Comment[]>([]);
  let laden = $state(true);
  let verfuegbar = $state(true);
  let text = $state('');
  let sendet = $state(false);
  let fehler = $state<string | null>(null);

  async function csrf(): Promise<string> {
    const res = await fetch('/api/csrf', { credentials: 'same-origin' });
    return (await res.json()).token as string;
  }

  async function ladeKommentare() {
    try {
      const res = await fetch(`/api/comments?post_id=${postId}`, { credentials: 'same-origin' });
      if (res.status === 404) {
        verfuegbar = false;
        return;
      }
      const data = await res.json();
      comments = data.comments ?? [];
    } catch {
      verfuegbar = false;
    } finally {
      laden = false;
    }
  }

  onMount(ladeKommentare);

  async function senden(e: Event) {
    e.preventDefault();
    if (!text.trim()) return;
    sendet = true;
    fehler = null;
    try {
      const token = await csrf();
      const res = await fetch('/api/comments', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': token },
        body: JSON.stringify({ post_id: postId, content: text }),
      });
      const data = await res.json();
      if (!res.ok) {
        fehler = data.meldung ?? 'Fehler beim Senden';
        return;
      }
      comments = [...comments, data.comment];
      text = '';
    } catch {
      fehler = 'Fehler beim Senden';
    } finally {
      sendet = false;
    }
  }
</script>

{#if verfuegbar}
  <section class="karte kommentare">
    <h2>Kommentare</h2>
    {#if laden}
      <p class="muted">Laedt ...</p>
    {:else}
      {#if comments.length === 0}
        <p class="muted">Noch keine Kommentare.</p>
      {:else}
        <ul>
          {#each comments as k (k.id)}
            <li><strong>{k.author_name}</strong>: {k.content}</li>
          {/each}
        </ul>
      {/if}
      <form onsubmit={senden}>
        <textarea bind:value={text} rows="2" placeholder="Kommentar schreiben (Anmeldung noetig) ..."
        ></textarea>
        {#if fehler}<p class="err">{fehler}</p>{/if}
        <button class="btn" type="submit" disabled={sendet}>
          {sendet ? 'Sendet ...' : 'Absenden'}
        </button>
      </form>
    {/if}
  </section>
{/if}

<style>
  .kommentare {
    margin-top: 1.2rem;
  }

  .kommentare h2 {
    margin-top: 0;
    font-size: 1.1rem;
  }

  ul {
    list-style: none;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin: 0 0 1rem;
  }

  textarea {
    width: 100%;
    padding: 0.6rem;
    background: var(--panel-2);
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text);
    font: inherit;
    margin-bottom: 0.6rem;
  }
</style>
