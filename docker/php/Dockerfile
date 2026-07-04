# ============================================================
# Knospe - PHP-8.5-Image (CLI) mit PostgreSQL-Anbindung und Xdebug
# ============================================================
# Bewusst schlank: eingebauter PHP-Server statt nginx/fpm, damit der Weg
# einer Anfrage komplett sichtbar bleibt. Für Produktion siehe die Doku.
# Lern mehr: docs/06-deployment/01-docker-compose-detailliert.md
FROM php:8.5-cli

# Systempakete und PHP-Erweiterungen für PostgreSQL.
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev git unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Xdebug 3: Port 9003, nur bei Bedarf (Trigger), Ziel ist der Docker-Host.
# Wichtig: Port 9003 (nicht 9000 - das war Xdebug 2).
RUN { \
      echo "xdebug.mode=debug"; \
      echo "xdebug.start_with_request=trigger"; \
      echo "xdebug.client_host=host.docker.internal"; \
      echo "xdebug.client_port=9003"; \
    } > /usr/local/etc/php/conf.d/knospe-xdebug.ini

# Composer aus dem offiziellen Image übernehmen (kein globaler Installer nötig).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app/backend
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]
