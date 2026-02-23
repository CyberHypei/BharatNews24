FROM php:8.3-fpm

# System deps for MySQL
RUN apt-get update && apt-get install -y \
    gnupg2 \
    unixodbc-dev \
    libmariadb-dev-compat \
    libzip-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

# MySQL PDO + extensions (KEY FIX)
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install pdo_mysql mysqli pdo zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

WORKDIR /var/www/html