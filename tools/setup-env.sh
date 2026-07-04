#!/bin/sh
# ============================================================
# Knospe - .env erzeugen (pfad-eindeutig, kollisionsfrei)
# ============================================================
# Leitet Compose-Projektname und freie Host-Ports aus dem absoluten
# Projektpfad ab (portabel per cksum), damit sich zwei Installationen
# desselben Projekts nie stoeren. Eine vorhandene .env wird NIE
# ueberschrieben. Nutzt nur POSIX-Werkzeuge, laeuft daher auf allen
# gaengigen Systemen.
# Lern mehr: docs/06-deployment/06-environment-verwaltung.md
set -eu

ROOT_DIR="$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)"
ENV_FILE="$ROOT_DIR/.env"

if [ -f "$ENV_FILE" ]; then
  echo "[setup-env] .env existiert bereits - unveraendert gelassen."
  exit 0
fi

# Pfad-Hash als Grundlage fuer eindeutige Namen und Ports.
HASH=$(printf '%s' "$ROOT_DIR" | cksum | awk '{print $1}')
OFFSET=$(( HASH % 400 ))

PHP_PORT=$(( 8000 + OFFSET ))
VITE_PORT=$(( 5173 + OFFSET ))
DB_PORT=$(( 15432 + OFFSET ))

# Kurzer, compose-konformer Projektname (nur Kleinbuchstaben/Ziffern/Bindestrich).
SHORT=$(printf '%s' "$HASH" | tail -c 6)
PROJECT="knospe-$SHORT"

# Geheimnisse erzeugen (mit Fallback ohne openssl).
gen_secret() {
  openssl rand -hex 24 2>/dev/null || (head -c 24 /dev/urandom | od -An -tx1 | tr -d ' \n')
}
APP_SECRET=$(gen_secret)
DB_PASSWORD=$(gen_secret)

# Datei mit restriktiven Rechten anlegen (enthaelt Geheimnisse).
umask 077
cat > "$ENV_FILE" <<EOF
# Automatisch erzeugt von tools/setup-env.sh - pfad-eindeutig.
# Nicht einchecken. Eine vorhandene Datei wird nie ueberschrieben.
COMPOSE_PROJECT_NAME=$PROJECT

PHP_PORT=$PHP_PORT
VITE_PORT=$VITE_PORT
DB_PORT=$DB_PORT

APP_ENV=development
APP_DEBUG=true
APP_SECRET=$APP_SECRET

POSTGRES_DB=knospe
POSTGRES_USER=knospe
POSTGRES_PASSWORD=$DB_PASSWORD
EOF

echo "[setup-env] .env erzeugt:"
echo "  Projektname:   $PROJECT"
echo "  PHP-API-Port:  $PHP_PORT"
echo "  Vite-Port:     $VITE_PORT"
echo "  Postgres-Port: $DB_PORT"
