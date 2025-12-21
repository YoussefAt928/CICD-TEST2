# ---------- Stage 1: Composer ----------
FROM composer:2 AS composer

# ---------- Stage 2: PHP Runtime ----------
FROM php:8.2-fpm

USER root

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    nginx \
 && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy composer binary from stage 1
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www

USER www-data
