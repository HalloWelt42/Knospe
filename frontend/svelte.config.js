import { vitePreprocess } from '@sveltejs/vite-plugin-svelte';

// Svelte-Konfiguration. vitePreprocess erlaubt TypeScript in .svelte-Dateien.
export default {
  preprocess: vitePreprocess(),
};
