<script lang="ts">
  // Anmeldeformular. Bei Erfolg zurueck zur Startseite.
  // Lern mehr: docs/04-frontend/08-forms-und-validierung.md
  import { anmelden } from '../lib/auth.svelte';
  import { navigate } from '../lib/router.svelte';
  import { ApiError } from '../lib/api';

  let email = $state('demo@knospe.local');
  let password = $state('');
  let sendet = $state(false);
  let fehler = $state<string | null>(null);

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
</script>

<h1>Anmelden</h1>

<form class="karte" onsubmit={absenden}>
  <div class="field">
    <label for="email">E-Mail</label>
    <input id="email" type="email" bind:value={email} autocomplete="username" />
  </div>
  <div class="field">
    <label for="password">Passwort</label>
    <input id="password" type="password" bind:value={password} autocomplete="current-password" />
  </div>
  {#if fehler}<p class="err">{fehler}</p>{/if}
  <button class="btn" type="submit" disabled={sendet}>
    {sendet ? 'Meldet an ...' : 'Anmelden'}
  </button>
</form>

<p class="muted">
  Noch kein Konto? <a href="#/register">Jetzt registrieren</a>.
  <br />
  Zum Ausprobieren: demo@knospe.local / passwort
</p>
