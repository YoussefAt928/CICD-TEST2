# ---------- Stage 1: Composer ----------
FROM docker.io/library/composer:2 AS composer

WORKDIR /app

# Copy composer files first (better cache)
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress

# Copy the rest of the application
COPY . .

# ---------- Stage 2: PHP Runtime ----------
FROM docker.io/library/php:8.2-fpm

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

WORKDIR /var/www

# Copy app WITH vendor from composer stage
COPY --from=composer /app /var/www

RUN chown -R www-data:www-data /var/www

USER www-data

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
