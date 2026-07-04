// Gemeinsame Datentypen fuer die Oberflaeche. Sie spiegeln die JSON-Antworten
// der PHP-API wider.
// Lern mehr: docs/04-frontend/02-typescript-in-svelte.md

export interface User {
  id: number;
  email: string;
  displayName: string;
  createdAt: string;
}

export interface Post {
  id: number;
  authorId: number;
  authorName: string | null;
  title: string;
  body: string;
  createdAt: string;
}
