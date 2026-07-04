import { mount } from 'svelte';
import App from './App.svelte';

// Schrift (Chakra Petch) und Icons (Font Awesome) ueber npm-Pakete, kein CDN.
import '@fontsource/chakra-petch/400.css';
import '@fontsource/chakra-petch/600.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

// Svelte 5: Die Anwendung wird ueber mount() eingehaengt.
// Lern mehr: docs/04-frontend/01-svelte5-komponenten-intro.md
const app = mount(App, {
  target: document.getElementById('app')!,
});

export default app;
