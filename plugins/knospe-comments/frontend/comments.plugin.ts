// Frontend-Registrierung des Kommentar-Plugins.
//
// Vite sammelt diese Datei über import.meta.glob ein (siehe
// frontend/src/lib/plugins.ts). Der Standard-Export sagt: "diese Komponente
// gehört an die Stelle post.detail.below". Mehr braucht es nicht.
// Lern mehr: ../../../docs/05-plugins/01-plugin-system-konzept.md
import CommentsPanel from './CommentsPanel.svelte';

export default [{ slot: 'post.detail.below', component: CommentsPanel }];
