FROM php:8.0-fpm

RUN apt-get update && \
    apt-get install -y \
    libpq-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    && pecl install \
    xdebug \
    && docker-php-ext-enable \
    xdebug

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

RUN apt-get update && \
    apt-get install -y wget && \
    wget https://phar.phpunit.de/phpunit-9.phar && \
    chmod +x phpunit-9.phar && \
    mv phpunit-9.phar /usr/local/bin/phpunit