FROM node:22-alpine AS assets
WORKDIR /app
ENV NODE_OPTIONS=--max-old-space-size=1536
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund --legacy-peer-deps
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build && rm -f public/hot

FROM serversideup/php:8.4-fpm-nginx AS app

USER root
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev --optimize-autoloader --no-interaction \
        --no-scripts --no-autoloader --prefer-dist

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN rm -f public/hot \
    && composer dump-autoload --optimize --no-dev --classmap-authoritative \
    && mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        database/sqlite \
    && chown -R www-data:www-data storage bootstrap/cache public/build database \
    && chmod -R 775 storage bootstrap/cache database/sqlite

USER www-data
