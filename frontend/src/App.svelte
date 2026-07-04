<script lang="ts">
  // Wurzelkomponente: lädt Sitzung und CSRF-Token, richtet den Router ein
  // und zeigt je nach Route die passende Ansicht.
  // Lern mehr: ./docs/04-frontend/01-svelte5-komponenten-intro.md
  import { onMount } from 'svelte';
  import Nav from './components/Nav.svelte';
  import PostsView from './components/PostsView.svelte';
  import PostDetail from './components/PostDetail.svelte';
  import LoginView from './components/LoginView.svelte';
  import RegisterView from './components/RegisterView.svelte';
  import PluginsView from './components/PluginsView.svelte';
  import { ladeAnmeldung } from './lib/auth.svelte';
  import { route, initRouter } from './lib/router.svelte';
  import { api } from './lib/api';

  let bereit = $state(false);

  onMount(async () => {
    initRouter();
    await api.ensureCsrf();
    await ladeAnmeldung();
    bereit = true;
  });
</script>

<Nav />

<main>
  {#if !bereit}
    <p class="muted">Lädt ...</p>
  {:else if route.name === 'login'}
    <LoginView />
  {:else if route.name === 'register'}
    <RegisterView />
  {:else if route.name === 'plugins'}
    <PluginsView />
  {:else if route.name === 'post' && route.id !== undefined}
    {#key route.id}
      <PostDetail id={route.id} />
    {/key}
  {:else}
    <PostsView />
  {/if}
</main>
