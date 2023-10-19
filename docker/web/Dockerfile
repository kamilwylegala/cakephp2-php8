FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    && apt-get install -y libpq-dev libzip-dev unzip openssl libmcrypt-dev libmemcached-dev locales \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql pdo_pgsql zip \
    && pecl install apcu redis memcached mcrypt xdebug \
    && docker-php-ext-enable redis memcached mcrypt \
    && docker-php-ext-enable xdebug \
    && echo "extension=apcu.so" >> /usr/local/etc/php/php.ini \
    && echo "apc.enable_cli = 1" >> /usr/local/etc/php/php.ini

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN sed -i '/de_DE /s/^# //g' /etc/locale.gen \
    && sed -i '/es_ES /s/^# //g' /etc/locale.gen \
    && locale-gen

ENV APACHE_DOCUMENT_ROOT /var/www/html/app/webroot

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
