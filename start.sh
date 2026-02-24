#!/bin/sh

echo "Waiting for MySQL..."
until php -r "new PDO('mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD');" 2>/dev/null; do
  sleep 2
  echo "MySQL not ready, retrying..."
done

echo "MySQL ready!"
php artisan config:clear
php artisan migrate --force
apache2-foreground