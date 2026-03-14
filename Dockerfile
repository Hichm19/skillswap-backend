FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev default-mysql-client

RUN docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --optimize-autoloader

RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]
