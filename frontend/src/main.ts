import { mount } from 'svelte';
import App from './App.svelte';

// Schrift (Barlow) und Icons (Font Awesome) über npm-Pakete, kein CDN.
import '@fontsource/barlow/400.css';
import '@fontsource/barlow/600.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

// Globale Gestaltung.
import './app.css';

// Svelte 5: Die Anwendung wird über mount() eingehängt.
// Lern mehr: ../../docs/04-frontend/01-svelte5-komponenten-intro.md
const app = mount(App, {
  target: document.getElementById('app')!,
});

export default app;
