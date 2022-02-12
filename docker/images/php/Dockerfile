FROM php:8.1-fpm

RUN apt-get update && apt-get install -y git
RUN docker-php-ext-install pdo_mysql

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN chown 1000:1000 /var/www/html/