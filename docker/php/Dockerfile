FROM php:8.1-fpm-buster
ENV ACCEPT_EULA=Y
RUN apt-get update && apt-get install -y gnupg2
RUN docker-php-ext-install bcmath mysqli pdo_mysql
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y curl git unzip fontconfig freetype*
RUN curl -sS https://getcomposer.org/installer -o ~/composer-setup.php
RUN php ~/composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9007" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "memory_limit=2048M" >> /usr/local/etc/php/conf.d/memory-limit-php.ini
COPY uploads.ini $PHP_INI_DIR/conf.d/