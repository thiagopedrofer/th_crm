FROM php:8.2-apache

# Evitar prompts interativos e instalar dependências básicas
RUN apt-get update && apt-get install -y \
    apt-utils \
    git \
    zip \
    unzip \
    libpq-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    gnupg \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip \
    && docker-php-ext-enable pdo_mysql \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copiar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
