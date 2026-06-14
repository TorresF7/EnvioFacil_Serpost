FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
        git unzip icu-data-full \
        libzip-dev oniguruma-dev sqlite-dev \
    && docker-php-ext-install -j"$(nproc)" \
        pdo pdo_sqlite mbstring zip bcmath opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini

RUN sed -i 's/^user = .*/user = root/; s/^group = .*/group = root/' \
        /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm", "-R"]
