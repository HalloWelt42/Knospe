// Frontend-Registry fuer Plugin-Bausteine.
//
// Wichtig zu verstehen: Svelte wird zur BUILD-Zeit von Vite kompiliert. Das
// PHP-Backend kann der fertigen Oberflaeche also keine .svelte-Datei zur
// Laufzeit "nachreichen". Stattdessen sammelt Vite hier per import.meta.glob
// alle Registrierungsdateien der Plugins ein (plugins/<id>/frontend/*.plugin.ts).
//
// Jede Registrierungsdatei exportiert als Standard eine Liste von Eintraegen,
// die sagen: "diese Komponente gehoert an diese Stelle (Slot)". Gibt es noch
// kein Plugin, bleibt die Liste einfach leer.
//
// Lern mehr: docs/05-plugins/01-plugin-system-konzept.md
import type { Component } from 'svelte';

export interface SlotEntry {
  slot: string;
  component: Component<any>;
  order?: number;
}

const module = import.meta.glob('../../../plugins/*/frontend/*.plugin.ts', { eager: true });

const eintraege: SlotEntry[] = [];
for (const mod of Object.values(module) as Array<{ default?: SlotEntry[] }>) {
  if (Array.isArray(mod.default)) {
    eintraege.push(...mod.default);
  }
}

export function componentsForSlot(slot: string): SlotEntry[] {
  return eintraege
    .filter((e) => e.slot === slot)
    .sort((a, b) => (a.order ?? 10) - (b.order ?? 10));
}
