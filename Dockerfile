FROM php:8.3-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# MySQL extensions
RUN apt-get update && apt-get install -y \
    libmariadb-dev-compat libzip-dev curl \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install pdo_mysql mysqli zip

# Set Laravel public/ as docroot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/conf-available/*.conf

# KEY FIX: Apache listens on $PORT (Render sets PORT=10000)
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' \
    /etc/apache2/sites-available/000-default.conf

# Copy app + install deps
COPY . /var/www/html
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000