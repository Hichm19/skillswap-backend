FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev

RUN docker-php-ext-install pdo pdo_mysql zip

COPY . .

RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
