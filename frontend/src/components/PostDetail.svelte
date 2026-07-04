<script lang="ts">
  // Einzelansicht eines Beitrags. Unterhalb gibt es einen Bereich, den
  // Plugins füllen können (ab Meilenstein M5/M6, z.B. Kommentare).
  import { onMount } from 'svelte';
  import { api } from '../lib/api';
  import type { Post } from '../lib/types';
  import PluginSlot from './PluginSlot.svelte';

  let { id }: { id: number } = $props();

  let post = $state<Post | null>(null);
  let laden = $state(true);
  let fehler = $state<string | null>(null);

  onMount(async () => {
    try {
      const antwort = await api.getPost(id);
      post = antwort.post;
    } catch (e) {
      fehler = e instanceof Error ? e.message : 'Fehler beim Laden';
    } finally {
      laden = false;
    }
  });

  const datum = $derived(
    post ? new Date(post.createdAt.replace(' ', 'T')).toLocaleString('de-DE') : '',
  );
</script>

<p><a href="#/">&larr; Zurück</a></p>

{#if laden}
  <p class="muted">Lädt ...</p>
{:else if fehler}
  <p class="err">{fehler}</p>
{:else if post}
  <article class="karte">
    <h1>{post.title}</h1>
    <p class="meta">von {post.authorName ?? 'Unbekannt'} - {datum}</p>
    <p class="text">{post.body}</p>
  </article>

  <!-- Plugin-Bereich: registrierte Komponenten für diese Stelle. -->
  <PluginSlot name="post.detail.below" props={{ postId: post.id }} />
{/if}

<style>
  .meta {
    color: var(--muted);
    font-size: 0.85rem;
  }

  .text {
    white-space: pre-wrap;
  }
</style>
