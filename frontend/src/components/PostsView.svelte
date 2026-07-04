<script lang="ts">
  // Startseite: Liste der Beiträge und (bei Anmeldung) ein Formular zum Anlegen.
  // Lern mehr: ../../../docs/04-frontend/08-forms-und-validierung.md
  import { onMount } from 'svelte';
  import { api, ApiError } from '../lib/api';
  import type { Post } from '../lib/types';
  import { auth } from '../lib/auth.svelte';
  import PostItem from './PostItem.svelte';

  let posts = $state<Post[]>([]);
  let laden = $state(true);
  let ladefehler = $state<string | null>(null);

  let titel = $state('');
  let text = $state('');
  let sendet = $state(false);
  let felder = $state<Record<string, string>>({});
  let formfehler = $state<string | null>(null);

  onMount(ladePosts);

  async function ladePosts() {
    laden = true;
    ladefehler = null;
    try {
      const { posts: geladen } = await api.listPosts();
      posts = geladen;
    } catch (e) {
      ladefehler = e instanceof Error ? e.message : 'Fehler beim Laden';
    } finally {
      laden = false;
    }
  }

  async function anlegen(e: Event) {
    e.preventDefault();
    sendet = true;
    felder = {};
    formfehler = null;
    try {
      const { post } = await api.createPost(titel, text);
      posts = [post, ...posts];
      titel = '';
      text = '';
    } catch (e) {
      if (e instanceof ApiError) {
        felder = e.fields;
        formfehler = e.message;
      } else {
        formfehler = 'Unbekannter Fehler';
      }
    } finally {
      sendet = false;
    }
  }

  function entfernt(id: number) {
    posts = posts.filter((p) => p.id !== id);
  }

  function ersetzt(aktualisiert: Post) {
    posts = posts.map((p) => (p.id === aktualisiert.id ? aktualisiert : p));
  }
</script>

<h1>Beiträge</h1>

{#if auth.user}
  <form class="karte anlegen" onsubmit={anlegen}>
    <h2>Neuer Beitrag</h2>
    <div class="field">
      <label for="titel">Titel</label>
      <input id="titel" bind:value={titel} maxlength="255" />
      {#if felder.title}<span class="fehler">{felder.title}</span>{/if}
    </div>
    <div class="field">
      <label for="text">Text</label>
      <textarea id="text" rows="4" bind:value={text}></textarea>
      {#if felder.body}<span class="fehler">{felder.body}</span>{/if}
    </div>
    {#if formfehler}<p class="err">{formfehler}</p>{/if}
    <button class="btn" type="submit" disabled={sendet}>
      {sendet ? 'Speichert ...' : 'Veröffentlichen'}
    </button>
  </form>
{:else}
  <p class="muted">
    Zum Schreiben bitte <a href="/login">anmelden</a> oder
    <a href="/register">registrieren</a>.
  </p>
{/if}

{#if laden}
  <p class="muted">Lädt ...</p>
{:else if ladefehler}
  <p class="err">{ladefehler}</p>
{:else if posts.length === 0}
  <p class="muted">Noch keine Beiträge.</p>
{:else}
  <ul class="liste">
    {#each posts as post (post.id)}
      <li><PostItem {post} onDeleted={entfernt} onUpdated={ersetzt} /></li>
    {/each}
  </ul>
{/if}

<style>
  .anlegen {
    margin-bottom: 1.5rem;
  }

  .anlegen h2 {
    margin-top: 0;
    font-size: 1.05rem;
  }

  .liste {
    list-style: none;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
</style>
