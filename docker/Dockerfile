FROM php:8.2.27-fpm-alpine3.20

ARG APP_ENV

RUN apk add --no-cache git postgresql-dev libpq

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions zip bcmath pdo_pgsql pcov @composer && \
    rm /usr/local/bin/install-php-extensions

WORKDIR /app