# ---------- Stage 1: Composer ----------
FROM docker.io/library/composer:2 AS composer

# ---------- Stage 2: PHP Runtime ----------
FROM php:8.2-fpm

USER root

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN chown -R www-data:www-data /var/www
USER www-data

EXPOSE 80
EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
