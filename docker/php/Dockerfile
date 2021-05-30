FROM 5.6-fpm-jessie

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
RUN apt install git nano
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo 'memory_limit=4096M' >> /usr/local/etc/php/php.ini
RUN echo 'date.timezone="UTC"' >> /usr/local/etc/php/php.ini
RUN pecl install apcu-4.0.11
RUN echo 'extension=apcu' >> /usr/local/etc/php/php.ini