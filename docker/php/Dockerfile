FROM php:5.6-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions gd xdebug pdo_mysql imagick apcu

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo 'memory_limit=4096M' >> /usr/local/etc/php/php.ini
RUN echo 'date.timezone="UTC"' >> /usr/local/etc/php/php.ini
