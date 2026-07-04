import { defineConfig } from 'vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';

// Vite-Konfiguration für die Knospe-SPA.
// - Der Dev-Proxy leitet /api an das PHP-Backend weiter (same-origin, kein CORS).
// - fs.allow erlaubt Vite den Zugriff auf den plugins/-Ordner außerhalb der
//   Frontend-Wurzel, damit Plugin-Frontends eingebunden werden können.
// Lern mehr: docs/04-frontend/05-vite-und-tooling.md
export default defineConfig({
  plugins: [svelte()],
  server: {
    host: true,
    port: 5173,
    proxy: {
      '/api': {
        target: process.env.API_PROXY_TARGET || 'http://localhost:8000',
        changeOrigin: true,
      },
    },
    fs: {
      allow: ['..'],
    },
  },
});
