<script lang="ts">
  // Rendert alle Plugin-Komponenten, die für eine bestimmte Stelle (Slot)
  // registriert sind. Die übergebenen Props werden an jede Komponente gereicht.
  // Lern mehr: ./docs/05-plugins/01-plugin-system-konzept.md
  import { componentsForSlot } from '../lib/plugins';

  let { name, props = {} }: { name: string; props?: Record<string, unknown> } = $props();

  const slots = $derived(componentsForSlot(name));
</script>

{#each slots as eintrag (eintrag)}
  {@const Komponente = eintrag.component}
  <Komponente {...props} />
{/each}
