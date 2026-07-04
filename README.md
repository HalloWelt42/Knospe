# Knospe

Ein Lehr-Boilerplate zum Verstehen: PHP 8.5 mit eigenem kleinen Kern (ohne Framework), eine getrennte Svelte-5-Oberflaeche und PostgreSQL als Datenbank. Ziel ist nicht das fertige Produkt, sondern das Begreifen jeder Ecke - der Code versteckt nichts, jede Datei erklaert ihr Warum, und ein ausfuehrliches deutsches Handbuch begleitet dich.

Fuer Einsteiger und Fortgeschrittene, die eigene Erweiterungen (Addons/Plugins) selbst und selbstgehostet entwickeln wollen.

## Stack

- Backend: PHP 8.5, eigener Mini-Kern nach PSR-Ideen (Container, Router, Middleware-Pipeline)
- Frontend: Svelte 5 (Runes) mit TypeScript, als eigene Oberflaeche gegen eine JSON-API
- Datenbank: PostgreSQL 16
- Betrieb: Docker Compose (Standard) oder lokale Installation (dokumentiert)

## Schnellstart (Docker)

Voraussetzung: Docker ist installiert.

```
./knospe setup     # .env erzeugen (eindeutige Ports/Namen) und Images bauen
./knospe up        # Stack starten
./knospe status    # zeigt die tatsaechlichen Adressen
```

`./knospe status` nennt die konkreten Ports (sie werden pfad-eindeutig gewaehlt, damit sich zwei Installationen nie stoeren). Danach:

- Oberflaeche: die dort genannte Frontend-Adresse
- API-Test: die dort genannte PHP-API-Adresse mit `/api/health`

Weitere Befehle: `./knospe` (ohne Argument) zeigt die Uebersicht - `down`, `logs`, `migrate`, `backup`, `restore`, `update`, `reset`.

## Dokumentation

Das komplette Handbuch liegt in [`docs/`](docs/). Einstieg ueber [`docs/README.md`](docs/README.md) mit Lernpfaden fuer Anfaenger, Fortgeschrittene, Betrieb und Frontend. Der Boilerplate-Code enthaelt Hinweis-Kommentare, die auf die passenden Leitfaeden zeigen.

## Projektstruktur (Kurz)

- `backend/` - PHP-Kern, Domaenen-Code, Migrationen, Tests
- `frontend/` - Svelte-5-Oberflaeche (Vite, TypeScript)
- `plugins/` - eigene Erweiterungen (mit Beispiel-Plugin)
- `docs/` - das Handbuch
- `docker/`, `docker-compose.yml`, `knospe` - Betrieb und Verwaltung

## Lizenz

**Nicht-kommerzielle Nutzung** - siehe [LICENSE](LICENSE).

Erlaubt: private Nutzung, Installation, persoenliche Anpassungen, Teilen mit Quellenangabe.
Verboten: kommerzielle Nutzung, Verkauf, Einbindung in kommerzielle Produkte.

---

## Unterstuetzen

Knospe ist ein privates Open-Source-Projekt. Kein Tracking, keine Werbung, keine Kompromisse.

Wenn dir das Projekt gefaellt, kannst du hier "Danke sagen":

[![Ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/HalloWelt42)

**Crypto:**

| Coin | Adresse |
|------|---------|
| BTC | `bc1qnd599khdkv3v3npmj9ufxzf6h4fzanny2acwqr` |
| DOGE | `DL7tuiYCqm3xQjMDXChdxeQxqUGMACn1ZV` |
| ETH | `0x8A28fc47bFFFA03C8f685fa0836E2dBe1CA14F27` |

Copyright (c) 2026 HalloWelt42
