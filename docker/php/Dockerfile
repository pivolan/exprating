FROM php:5.6-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apk --no-cache add \
        nano \
        git \
        bash \
        php5-apcu
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo 'memory_limit=4096M' >> /usr/local/etc/php/php.ini
RUN echo 'date.timezone="UTC"' >> /usr/local/etc/php/php.ini