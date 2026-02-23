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

# Copy app FIRST (artisan available)
COPY . /var/www/html
WORKDIR /var/www/html

# Composer LAST (post-autoload works)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# PHP-FPM
EXPOSE 9000  