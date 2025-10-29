FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    gnupg \
    autoconf \
    build-essential \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js 20 + npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Redis extension and PDO MySQL
RUN pecl install redis || true \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql

WORKDIR /var/www

# Copy Composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing app files
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www
