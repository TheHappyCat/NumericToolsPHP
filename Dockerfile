FROM composer:2 AS composer-binary

FROM php:8.5-cli-bookworm

COPY --from=composer-binary /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /app

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV XDEBUG_MODE=coverage

COPY composer.json composer.lock ./

RUN composer install --prefer-dist --no-progress --no-interaction

COPY . .

CMD ["composer", "test"]
