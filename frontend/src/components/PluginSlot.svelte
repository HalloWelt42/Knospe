<script lang="ts">
  // Rendert alle Plugin-Komponenten, die fuer eine bestimmte Stelle (Slot)
  // registriert sind. Die uebergebenen Props werden an jede Komponente gereicht.
  // Lern mehr: docs/05-plugins/01-plugin-system-konzept.md
  import { componentsForSlot } from '../lib/plugins';

  let { name, props = {} }: { name: string; props?: Record<string, unknown> } = $props();

  const eintraege = $derived(componentsForSlot(name));
</script>

{#each eintraege as eintrag (eintrag)}
  {@const Komponente = eintrag.component}
  <Komponente {...props} />
{/each}
