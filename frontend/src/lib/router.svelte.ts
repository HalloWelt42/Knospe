// Ein winziger Router auf Basis des URL-Fragments (#/...). Bewusst klein:
// er liest den aktuellen Pfad, lauscht auf Aenderungen und bietet navigate().
// Fuer eine SPA ohne SvelteKit reicht das voellig.
// Lern mehr: docs/04-frontend/07-routing-sveltekit.md

export interface Route {
  name: 'home' | 'login' | 'register' | 'post' | 'plugins';
  id?: number;
}

function parse(): Route {
  const hash = location.hash.replace(/^#/, '') || '/';

  if (hash === '/login') return { name: 'login' };
  if (hash === '/register') return { name: 'register' };
  if (hash === '/plugins') return { name: 'plugins' };

  const match = hash.match(/^\/posts\/(\d+)$/);
  if (match) return { name: 'post', id: Number(match[1]) };

  return { name: 'home' };
}

export const route = $state<Route>(parse());

export function initRouter(): void {
  const update = () => {
    const next = parse();
    route.name = next.name;
    route.id = next.id;
  };
  window.addEventListener('hashchange', update);
  update();
}

export function navigate(path: string): void {
  location.hash = path;
}
