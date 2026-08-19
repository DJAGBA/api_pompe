FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod

# Installation des dépendances sans scripts pour éviter les erreurs de build
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --optimize-autoloader

# Création du dossier var et gestion des permissions
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

# Port par défaut si $PORT n'est pas fourni par l'environnement
ENV PORT=8000
EXPOSE ${PORT}

# Lancement du serveur PHP sur le port dynamique $PORT
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8000} -t public"]