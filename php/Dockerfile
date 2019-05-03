FROM php:7.3-fpm-alpine3.9
ENV RUNTIME_PACKAGES="pdo_mysql mysqli php-mysqlnd mbstring gd iconv"
COPY php.ini /etc/
RUN docker-php-ext-install pdo_mysql mysqli mbstring
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
