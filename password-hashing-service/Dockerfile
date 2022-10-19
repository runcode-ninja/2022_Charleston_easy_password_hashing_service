FROM php:8.1.1-apache

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    rm "$PHP_INI_DIR/php.ini-production" && \
    rm /var/log/apache2/error.log && \
    rm /var/log/apache2/access.log

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY src/ /var/www/html/

WORKDIR /var/www

RUN apt-get update && apt-get -y install libz-dev libzip-dev unzip && \
    pecl install zip && \
    composer require "twig/twig:^3.0" && \
    apt-get clean

RUN mkdir -p /var/www/uploads && \
    chmod 777 /var/www/uploads

COPY php.ini /usr/local/etc/php/php.ini

COPY flag.txt /flag.txt