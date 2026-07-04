// Ein winziger Router auf Basis der Pfad-URL (History API). Bewusst klein:
// er liest den aktuellen Pfad, faengt interne Link-Klicks ab (kein volles
// Neuladen) und bietet navigate(). So sehen die Adressen sauber aus
// (z.B. /register statt /#/register).
// Lern mehr: ../../../docs/04-frontend/07-routing-sveltekit.md

export interface Route {
  name: 'home' | 'login' | 'register' | 'post' | 'plugins';
  id?: number;
}

function parse(): Route {
  const path = location.pathname || '/';

  if (path === '/login') return { name: 'login' };
  if (path === '/register') return { name: 'register' };
  if (path === '/plugins') return { name: 'plugins' };

  const match = path.match(/^\/posts\/(\d+)$/);
  if (match) return { name: 'post', id: Number(match[1]) };

  return { name: 'home' };
}

export const route = $state<Route>(parse());

function aktualisieren(): void {
  const next = parse();
  route.name = next.name;
  route.id = next.id;
}

export function navigate(path: string): void {
  if (path !== location.pathname) {
    history.pushState({}, '', path);
  }
  aktualisieren();
}

export function initRouter(): void {
  // Vor- und Zuruecknavigieren im Browser.
  window.addEventListener('popstate', aktualisieren);

  // Interne Links (beginnend mit /) client-seitig behandeln, damit die Seite
  // nicht komplett neu laedt.
  document.addEventListener('click', (event) => {
    if (
      event.defaultPrevented ||
      event.button !== 0 ||
      event.metaKey ||
      event.ctrlKey ||
      event.shiftKey ||
      event.altKey
    ) {
      return;
    }

    const link = (event.target as HTMLElement).closest('a');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href || !href.startsWith('/') || link.target === '_blank') return;

    event.preventDefault();
    navigate(href);
  });

  aktualisieren();
}
