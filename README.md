<p align="center"><img src="docs/knospe-logo.svg" alt="Knospe" width="96" height="96" /></p>

# Knospe

Ein Lehr-Boilerplate zum Verstehen: PHP 8.5 mit eigenem kleinen Kern (ohne Framework), eine getrennte Svelte-5-Oberfläche und PostgreSQL als Datenbank. Ziel ist nicht das fertige Produkt, sondern das Begreifen jeder Ecke - der Code versteckt nichts, jede Datei erklärt ihr Warum, und ein ausführliches deutsches Handbuch begleitet dich.

Für Einsteiger und Fortgeschrittene, die eigene Erweiterungen (Addons/Plugins) selbst und selbstgehostet entwickeln wollen.

## Stack

- Backend: PHP 8.5, eigener Mini-Kern nach PSR-Ideen (Container, Router, Middleware-Pipeline)
- Frontend: Svelte 5 (Runes) mit TypeScript, als eigene Oberfläche gegen eine JSON-API
- Datenbank: PostgreSQL 16
- Betrieb: Docker Compose (Standard) oder lokale Installation (dokumentiert)

## Schnellstart (Docker)

Voraussetzung: Docker ist installiert.

```
./knospe setup     # .env erzeugen (eindeutige Ports/Namen) und Images bauen
./knospe up        # Stack starten
./knospe status    # zeigt die tatsächlichen Adressen
```

`./knospe status` nennt die konkreten Ports (sie werden pfad-eindeutig gewählt, damit sich zwei Installationen nie stören). Danach:

- Oberfläche: die dort genannte Frontend-Adresse
- API-Test: die dort genannte PHP-API-Adresse mit `/api/health`

Weitere Befehle: `./knospe` (ohne Argument) zeigt die Übersicht - `down`, `logs`, `migrate`, `backup`, `restore`, `update`, `reset`.

## Dokumentation

Das komplette Handbuch liegt in [`docs/`](docs/). Einstieg über [`docs/README.md`](docs/README.md) mit Lernpfaden für Anfänger, Fortgeschrittene, Betrieb und Frontend. Der Boilerplate-Code enthält Hinweis-Kommentare, die auf die passenden Leitfäden zeigen.

## Projektstruktur (Kurz)

- [`backend/`](backend/) - PHP-Kern, Domänen-Code, Migrationen, Tests
- [`frontend/`](frontend/) - Svelte-5-Oberfläche (Vite, TypeScript)
- [`plugins/`](plugins/) - eigene Erweiterungen (mit Beispiel-Plugin)
- [`docs/`](docs/) - das Handbuch
- [`docker/`](docker/), [`docker-compose.yml`](docker-compose.yml), [`knospe`](knospe) - Betrieb und Verwaltung

## Lizenz

**Nicht-kommerzielle Nutzung** - siehe [LICENSE](LICENSE).

Erlaubt: private Nutzung, Installation, persönliche Anpassungen, Teilen mit Quellenangabe.
Verboten: kommerzielle Nutzung, Verkauf, Einbindung in kommerzielle Produkte.

---

## Unterstützen

Knospe ist ein privates Open-Source-Projekt. Kein Tracking, keine Werbung, keine Kompromisse.

Wenn dir das Projekt gefällt, kannst du hier "Danke sagen":

[![Ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/HalloWelt42)

**Crypto:**

| Coin | Adresse |
|------|---------|
| BTC | `bc1qnd599khdkv3v3npmj9ufxzf6h4fzanny2acwqr` |
| DOGE | `DL7tuiYCqm3xQjMDXChdxeQxqUGMACn1ZV` |
| ETH | `0x8A28fc47bFFFA03C8f685fa0836E2dBe1CA14F27` |

Copyright (c) 2026 HalloWelt42
