// Typisierter API-Client für die PHP-JSON-API.
//
// Besonderheiten:
//  - Cookies (Sitzung) werden dank same-origin automatisch mitgeschickt.
//  - Bei verändernden Methoden wird das CSRF-Token als Header gesetzt; es
//    wird einmal über /api/csrf geholt und dann zwischengespeichert.
//  - Fehlerantworten der API werden zu einem ApiError mit Meldung und
//    optionalen Feldfehlern.
//
// Lern mehr: ../../../docs/04-frontend/03-api-aufrufe-fetch.md

import type { PluginInfo, Post, User } from './types';

let csrfToken: string | null = null;

export class ApiError extends Error {
  constructor(
    message: string,
    public status: number,
    public fields: Record<string, string> = {},
  ) {
    super(message);
    this.name = 'ApiError';
  }
}

export async function ensureCsrf(): Promise<string> {
  if (csrfToken) {
    return csrfToken;
  }
  const res = await fetch('/api/csrf', { credentials: 'same-origin' });
  const data = await res.json();
  csrfToken = data.token as string;
  return csrfToken;
}

async function request<T>(method: string, path: string, body?: unknown): Promise<T> {
  const headers: Record<string, string> = { Accept: 'application/json' };
  const options: RequestInit = { method, credentials: 'same-origin', headers };

  if (body !== undefined) {
    headers['Content-Type'] = 'application/json';
    options.body = JSON.stringify(body);
  }

  if (method !== 'GET' && method !== 'HEAD') {
    headers['X-CSRF-Token'] = await ensureCsrf();
  }

  const res = await fetch(`/api${path}`, options);
  const text = await res.text();
  const data = text ? JSON.parse(text) : null;

  if (!res.ok) {
    const message = (data && data.meldung) || `Fehler ${res.status}`;
    throw new ApiError(message, res.status, (data && data.felder) || {});
  }

  return data as T;
}

export const api = {
  ensureCsrf,
  me: () => request<{ user: User | null }>('GET', '/auth/me'),
  login: (email: string, password: string) =>
    request<{ user: User }>('POST', '/auth/login', { email, password }),
  register: (email: string, displayName: string, password: string) =>
    request<{ user: User }>('POST', '/auth/register', { email, displayName, password }),
  logout: () => request<{ status: string }>('POST', '/auth/logout'),
  listPosts: () => request<{ posts: Post[] }>('GET', '/posts'),
  getPost: (id: number) => request<{ post: Post }>('GET', `/posts/${id}`),
  createPost: (title: string, body: string) =>
    request<{ post: Post }>('POST', '/posts', { title, body }),
  updatePost: (id: number, title: string, body: string) =>
    request<{ post: Post }>('PUT', `/posts/${id}`, { title, body }),
  deletePost: (id: number) => request<{ status: string }>('DELETE', `/posts/${id}`),
  listPlugins: () => request<{ plugins: PluginInfo[] }>('GET', '/plugins'),
  enablePlugin: (id: string) => request<{ status: string }>('POST', `/plugins/${id}/enable`),
  disablePlugin: (id: string) => request<{ status: string }>('POST', `/plugins/${id}/disable`),
};
