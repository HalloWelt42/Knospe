// Geteilter Anmeldezustand als Svelte-5-Rune ($state in einem .svelte.ts-Modul).
// Statt eines klassischen Stores nutzen wir ein reaktives Objekt: Änderungen
// an seinen Feldern aktualisieren automatisch alle Komponenten, die es lesen.
// Lern mehr: ./docs/04-frontend/04-state-management-einfach.md

import { api } from './api';
import type { User } from './types';

export const auth = $state<{ user: User | null; bereit: boolean }>({
  user: null,
  bereit: false,
});

export async function ladeAnmeldung(): Promise<void> {
  try {
    const { user } = await api.me();
    auth.user = user;
  } catch {
    auth.user = null;
  } finally {
    auth.bereit = true;
  }
}

export async function anmelden(email: string, password: string): Promise<void> {
  const { user } = await api.login(email, password);
  auth.user = user;
}

export async function registrieren(
  email: string,
  displayName: string,
  password: string,
): Promise<void> {
  const { user } = await api.register(email, displayName, password);
  auth.user = user;
}

export async function abmelden(): Promise<void> {
  await api.logout();
  auth.user = null;
}
