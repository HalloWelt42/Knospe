#!/bin/sh
# ============================================================
# Knospe - .env erzeugen (eindeutiger Name + freie Ports)
# ============================================================
# Erzeugt eine projekt-eindeutige .env, falls noch keine existiert.
# Der Projektname traegt einen Postfix: entweder selbst gewählt (bei
# "./knospe setup" wird danach gefragt) oder automatisch aus dem
# Projektpfad abgeleitet (cksum). Vor dem Schreiben wird geprüft, ob
# Name oder Ports bereits belegt sind (Kollisionsprüfung), damit sich
# zwei Installationen nie stören. Eine vorhandene .env wird NIE
# überschrieben. Nutzt nur POSIX-Werkzeuge.
# Lern mehr: ./docs/06-deployment/06-environment-verwaltung.md
set -eu

ROOT_DIR="$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)"
ENV_FILE="$ROOT_DIR/.env"

if [ -f "$ENV_FILE" ]; then
  echo "[setup-env] .env existiert bereits - unverändert gelassen."
  exit 0
fi

# --- Argumente: --ask = interaktiv nachfragen; sonst optionaler Postfix ---
ASK=no
ARG_POSTFIX=""
for a in "$@"; do
  case "$a" in
    --ask) ASK=yes ;;
    *) ARG_POSTFIX="$a" ;;
  esac
done

# --- Hilfsfunktionen ---------------------------------------------------------

# Macht aus einer Eingabe einen gültigen Namensteil (klein, a-z 0-9 -).
sanitize() {
  printf '%s' "$1" | tr '[:upper:]' '[:lower:]' | tr -c 'a-z0-9-' '-' \
    | sed -e 's/-\{1,\}/-/g' -e 's/^-//' -e 's/-$//'
}

# Ist der Compose-Projektname schon von einem anderen Projekt belegt?
name_taken() {
  n="$1"
  command -v docker >/dev/null 2>&1 || return 1
  if docker ps -a --format '{{.Names}}'   2>/dev/null | grep -q "^${n}-"; then return 0; fi
  if docker volume ls --format '{{.Name}}' 2>/dev/null | grep -q "^${n}_"; then return 0; fi
  if docker network ls --format '{{.Name}}' 2>/dev/null | grep -q "^${n}_"; then return 0; fi
  return 1
}

# Lauscht schon jemand auf diesem Host-Port?
port_in_use() {
  p="$1"
  if command -v lsof >/dev/null 2>&1; then
    if lsof -nP -iTCP:"$p" -sTCP:LISTEN >/dev/null 2>&1; then return 0; fi
  fi
  if command -v docker >/dev/null 2>&1; then
    if docker ps --format '{{.Ports}}' 2>/dev/null | grep -q ":${p}->"; then return 0; fi
  fi
  return 1
}

# Nächsten freien Port ab dem Startwert finden.
free_port() {
  p="$1"
  while port_in_use "$p"; do p=$(( p + 1 )); done
  printf '%s' "$p"
}

gen_secret() {
  openssl rand -hex 24 2>/dev/null || (head -c 24 /dev/urandom | od -An -tx1 | tr -d ' \n')
}

# --- Pfad-Hash als Grundlage für Automatik-Werte ---------------------------
HASH=$(printf '%s' "$ROOT_DIR" | cksum | awk '{print $1}')
AUTO=$(printf '%s' "$HASH" | tail -c 6)

# --- Postfix bestimmen ------------------------------------------------------
if [ -n "$ARG_POSTFIX" ]; then
  # Nicht-interaktiv mit vorgegebenem Postfix.
  POSTFIX=$(sanitize "$ARG_POSTFIX")
  [ -n "$POSTFIX" ] || { echo "Ungültiger Postfix." >&2; exit 1; }
  if name_taken "knospe-$POSTFIX"; then
    echo "Name 'knospe-$POSTFIX' ist bereits belegt (anderes Docker-Projekt)." >&2
    echo "Bitte einen anderen Postfix wählen: ./knospe setup <postfix>" >&2
    exit 1
  fi
elif [ "$ASK" = yes ] && [ -t 0 ]; then
  # Interaktiv nachfragen, mit Kollisionsprüfung und Wiederholung.
  while true; do
    printf "Projekt-Postfix wählen (Enter = automatisch '%s'): " "$AUTO"
    read -r ANSWER || ANSWER=""
    [ -n "$ANSWER" ] || ANSWER="$AUTO"
    POSTFIX=$(sanitize "$ANSWER")
    if [ -z "$POSTFIX" ]; then
      echo "  Ungültig - erlaubt sind Buchstaben, Ziffern und Bindestrich."
      continue
    fi
    if name_taken "knospe-$POSTFIX"; then
      echo "  Name 'knospe-$POSTFIX' ist schon belegt - bitte einen anderen wählen."
      continue
    fi
    break
  done
else
  # Nicht-interaktiv ohne Vorgabe: automatisch aus dem Pfad.
  POSTFIX="$AUTO"
fi

PROJECT="knospe-$POSTFIX"

# --- Ports aus dem Pfad ableiten und auf frei prüfen -----------------------
OFFSET=$(( HASH % 400 ))
PHP_PORT=$(free_port $(( 8000 + OFFSET )))
VITE_PORT=$(free_port $(( 5173 + OFFSET )))
DB_PORT=$(free_port $(( 15432 + OFFSET )))

APP_SECRET=$(gen_secret)
DB_PASSWORD=$(gen_secret)

# Datei mit restriktiven Rechten anlegen (enthält Geheimnisse).
umask 077
cat > "$ENV_FILE" <<EOF
# Automatisch erzeugt von tools/setup-env.sh.
# Nicht einchecken. Eine vorhandene Datei wird nie überschrieben.
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
