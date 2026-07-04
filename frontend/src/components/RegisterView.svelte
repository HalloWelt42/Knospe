<script lang="ts">
  // Registrierungsformular mit Feldfehlern aus der API.
  // Lern mehr: docs/04-frontend/08-forms-und-validierung.md
  import { registrieren } from '../lib/auth.svelte';
  import { navigate } from '../lib/router.svelte';
  import { ApiError } from '../lib/api';

  let email = $state('');
  let displayName = $state('');
  let password = $state('');
  let sendet = $state(false);
  let felder = $state<Record<string, string>>({});
  let fehler = $state<string | null>(null);

  async function absenden(e: Event) {
    e.preventDefault();
    sendet = true;
    felder = {};
    fehler = null;
    try {
      await registrieren(email, displayName, password);
      navigate('/');
    } catch (e) {
      if (e instanceof ApiError) {
        felder = e.fields;
        fehler = e.message;
      } else {
        fehler = 'Registrierung fehlgeschlagen';
      }
    } finally {
      sendet = false;
    }
  }
</script>

<h1>Registrieren</h1>

<form class="karte" onsubmit={absenden}>
  <div class="field">
    <label for="r-email">E-Mail</label>
    <input id="r-email" type="email" bind:value={email} autocomplete="username" />
    {#if felder.email}<span class="fehler">{felder.email}</span>{/if}
  </div>
  <div class="field">
    <label for="r-name">Anzeigename</label>
    <input id="r-name" bind:value={displayName} autocomplete="nickname" />
    {#if felder.displayName}<span class="fehler">{felder.displayName}</span>{/if}
  </div>
  <div class="field">
    <label for="r-pw">Passwort (mind. 8 Zeichen)</label>
    <input id="r-pw" type="password" bind:value={password} autocomplete="new-password" />
    {#if felder.password}<span class="fehler">{felder.password}</span>{/if}
  </div>
  {#if fehler}<p class="err">{fehler}</p>{/if}
  <button class="btn" type="submit" disabled={sendet}>
    {sendet ? 'Legt an ...' : 'Konto anlegen'}
  </button>
</form>

<p class="muted">Schon ein Konto? <a href="#/login">Anmelden</a>.</p>
