<script lang="ts">
  // Kopfleiste mit Marke und Anmelde-Status.
  import { auth, abmelden } from '../lib/auth.svelte';
  import { navigate } from '../lib/router.svelte';

  async function raus() {
    await abmelden();
    navigate('/');
  }
</script>

<header class="nav">
  <a class="marke" href="#/"><i class="fa-solid fa-seedling"></i> Knospe</a>
  <nav>
    {#if auth.user}
      <span class="muted">Hallo, <strong>{auth.user.displayName}</strong></span>
      <button class="btn-ghost" onclick={raus}>Abmelden</button>
    {:else}
      <a href="#/login">Anmelden</a>
      <a href="#/register">Registrieren</a>
    {/if}
  </nav>
</header>

<style>
  .nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.9rem 1.5rem;
    background: var(--panel);
    border-bottom: 1px solid var(--border);
  }

  .marke {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text);
  }

  .marke:hover {
    text-decoration: none;
  }

  nav {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
</style>
