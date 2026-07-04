<script lang="ts">
  // Ein Beitrag in der Liste. Der Autor sieht Bearbeiten und Loeschen.
  // Statt eines Browser-Dialogs gibt es eine eigene Inline-Rueckfrage.
  // Lern mehr: docs/04-frontend/02-typescript-in-svelte.md
  import { api, ApiError } from '../lib/api';
  import type { Post } from '../lib/types';
  import { auth } from '../lib/auth.svelte';

  let {
    post,
    onDeleted,
    onUpdated,
  }: {
    post: Post;
    onDeleted: (id: number) => void;
    onUpdated: (post: Post) => void;
  } = $props();

  const eigen = $derived(auth.user?.id === post.authorId);

  let bearbeiten = $state(false);
  let titel = $state('');
  let text = $state('');
  let sendet = $state(false);
  let fehler = $state<string | null>(null);

  let loeschfrage = $state(false);

  function starteBearbeiten() {
    titel = post.title;
    text = post.body;
    fehler = null;
    bearbeiten = true;
  }

  async function speichern() {
    sendet = true;
    fehler = null;
    try {
      const { post: neu } = await api.updatePost(post.id, titel, text);
      onUpdated(neu);
      bearbeiten = false;
    } catch (e) {
      fehler = e instanceof ApiError ? e.message : 'Fehler beim Speichern';
    } finally {
      sendet = false;
    }
  }

  async function loeschen() {
    sendet = true;
    try {
      await api.deletePost(post.id);
      onDeleted(post.id);
    } catch (e) {
      fehler = e instanceof ApiError ? e.message : 'Fehler beim Loeschen';
      loeschfrage = false;
    } finally {
      sendet = false;
    }
  }

  function abbrechen() {
    titel = post.title;
    text = post.body;
    bearbeiten = false;
    fehler = null;
  }

  const datum = $derived(new Date(post.createdAt.replace(' ', 'T')).toLocaleString('de-DE'));
</script>

<article class="karte">
  {#if bearbeiten}
    <div class="field">
      <label for="t-{post.id}">Titel</label>
      <input id="t-{post.id}" bind:value={titel} maxlength="255" />
    </div>
    <div class="field">
      <label for="b-{post.id}">Text</label>
      <textarea id="b-{post.id}" rows="4" bind:value={text}></textarea>
    </div>
    {#if fehler}<p class="err">{fehler}</p>{/if}
    <div class="aktionen">
      <button class="btn" onclick={speichern} disabled={sendet}>Speichern</button>
      <button class="btn-ghost" onclick={abbrechen} disabled={sendet}>Abbrechen</button>
    </div>
  {:else}
    <header class="kopf">
      <h3><a href={`#/posts/${post.id}`}>{post.title}</a></h3>
      <span class="meta">von {post.authorName ?? 'Unbekannt'} - {datum}</span>
    </header>
    <p class="text">{post.body}</p>
    {#if fehler}<p class="err">{fehler}</p>{/if}
    {#if eigen}
      <div class="aktionen">
        <button class="btn-ghost" onclick={starteBearbeiten}>Bearbeiten</button>
        {#if loeschfrage}
          <span class="muted">Wirklich loeschen?</span>
          <button class="btn-danger" onclick={loeschen} disabled={sendet}>Ja, loeschen</button>
          <button class="btn-ghost" onclick={() => (loeschfrage = false)}>Nein</button>
        {:else}
          <button class="btn-danger" onclick={() => (loeschfrage = true)}>Loeschen</button>
        {/if}
      </div>
    {/if}
  {/if}
</article>

<style>
  .kopf h3 {
    margin: 0 0 0.15rem;
    font-size: 1.1rem;
  }

  .meta {
    color: var(--muted);
    font-size: 0.82rem;
  }

  .text {
    white-space: pre-wrap;
  }

  .aktionen {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-wrap: wrap;
  }
</style>
