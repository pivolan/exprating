FROM php:5.6-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apk --no-cache add \
        nano \
        git \
        bash \
        php5-apcu \
        bash
RUN docker-php-ext-install pdo pdo_mysql
RUN echo 'date.timezone="UTC"' >> /usr/local/etc/php/php.ini