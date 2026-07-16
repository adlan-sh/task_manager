# ---------------------------------------------- Build Time Arguments --------------------------------------------------
ARG PHP_VERSION="8.3"
ARG COMPOSER_VERSION="2.7.4"
ARG XDEBUG_VERSION="3.4.1"
ARG COMPOSER_AUTH
ARG APP_BASE_DIR="."

# -------------------------------------------------- Composer Image ----------------------------------------------------

FROM composer:${COMPOSER_VERSION} as composer

# ======================================================================================================================
#                                                   --- Base ---
# ---------------  This stage install needed extenstions, plugins and add all needed configurations  -------------------
# ======================================================================================================================

FROM php:${PHP_VERSION}-fpm-alpine AS base

# Required Args ( inherited from start of file, or passed at build )
ARG XDEBUG_VERSION

# Set SHELL flags for RUN commands to allow -e and pipefail
SHELL ["/bin/ash", "-eo", "pipefail", "-c"]

# ------------------------------------- Install Packages Needed Inside Base Image --------------------------------------
# Обновляем репозитории и устанавливаем runtime зависимости
RUN apk update && apk add --no-cache --upgrade \
    tini \
    fcgi \
    tzdata \
    curl \
    openssl

# ---------------------------------------- Install / Enable PHP Extensions ---------------------------------------------
# Устанавливаем ВСЕ зависимости ВМЕСТЕ с dev зависимостями
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    g++ \
    autoconf \
    linux-headers \
    make \
    && apk add --no-cache \
    libzip \
    libzip-dev \
    icu \
    icu-dev \
    postgresql \
    postgresql-dev \
    postgresql-client \
    libpng \
    libpng-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    freetype \
    freetype-dev \
    oniguruma \
    oniguruma-dev \
    libxml2 \
    libxml2-dev \
    libwebp \
    libwebp-dev

# Устанавливаем PHP расширения
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        intl \
        opcache \
        zip \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        bcmath \
        exif \
        mbstring \
        xml

# Устанавливаем PECL расширения
RUN pecl install apcu \
    && docker-php-ext-enable apcu \
    && pecl install xdebug-${XDEBUG_VERSION} \
    && rm -rf /tmp/pear

# Удаляем ТОЛЬКО build зависимости, оставляя runtime библиотеки
RUN apk del --no-network .build-deps \
    && rm -rf /var/cache/apk/* /tmp/*

# ------------------------------------------------- Permissions --------------------------------------------------------

RUN deluser --remove-home www-data && adduser -u1000 -D www-data && rm -rf /var/www /usr/local/etc/php-fpm.d/* && \
    mkdir -p /var/www/.composer /app && chown -R www-data:www-data /app /var/www/.composer; \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Add in Base PHP Config
COPY docker/app/php/base-*   $PHP_INI_DIR/conf.d

# ---------------------------------------------- PHP FPM Configuration -------------------------------------------------

# PHP-FPM config
COPY docker/app/fpm/*.conf  /usr/local/etc/php-fpm.d/

# --------------------------------------------------- Scripts ----------------------------------------------------------

COPY docker/app/entrypoint/*-base       \
     docker/app/post-build/*-base       \
     docker/app/pre-run/*-base          \
     docker/app/fpm/healthcheck-fpm     \
     docker/app/scripts/command-loop*   \
     /usr/local/bin/

RUN chmod +x /usr/local/bin/*-base /usr/local/bin/healthcheck-fpm /usr/local/bin/command-loop*

# ---------------------------------------------------- Composer --------------------------------------------------------

COPY --from=composer /usr/bin/composer /usr/bin/composer

# ----------------------------------------------------- MISC -----------------------------------------------------------

WORKDIR /app
USER www-data

# Common PHP Frameworks Env Variables
ENV APP_ENV prod
ENV APP_DEBUG 0
ENV TZ=Europe/Moscow

# Validate FPM config (must use the non-root user)
RUN php-fpm -t

# ---------------------------------------------------- HEALTH ----------------------------------------------------------

HEALTHCHECK CMD ["healthcheck-fpm"]

# -------------------------------------------------- ENTRYPOINT --------------------------------------------------------

ENTRYPOINT ["entrypoint-base"]
CMD ["php-fpm"]

# ======================================================================================================================
#                                                  --- Vendor ---
# ======================================================================================================================

FROM composer as vendor

ARG PHP_VERSION
ARG COMPOSER_AUTH
ARG APP_BASE_DIR

ENV COMPOSER_AUTH $COMPOSER_AUTH

WORKDIR /app

COPY $APP_BASE_DIR/composer.json composer.json
COPY $APP_BASE_DIR/composer.lock composer.lock

RUN composer config platform.php ${PHP_VERSION}; \
    composer install -n --no-progress --ignore-platform-reqs --no-dev --prefer-dist --no-scripts --no-autoloader

# ======================================================================================================================
# ==============================================  PRODUCTION IMAGE  ====================================================
# ======================================================================================================================

FROM base AS app

ARG APP_BASE_DIR
USER root

# Copy PHP Production Configuration
COPY docker/app/php/prod-*   $PHP_INI_DIR/conf.d/

# Copy Prod Scripts && delete xdebug
COPY docker/app/entrypoint/*-prod docker/app/post-build/*-prod docker/app/pre-run/*-prod \
     /usr/local/bin/

RUN chmod +x /usr/local/bin/*-prod && pecl uninstall xdebug && docker-php-ext-enable opcache

USER www-data

# ----------------------------------------------- Production Config -----------------------------------------------------

COPY --chown=www-data:www-data --from=vendor /vendor /app/vendor
COPY --chown=www-data:www-data $APP_BASE_DIR/ .

RUN composer install --optimize-autoloader --apcu-autoloader --no-dev -n --no-progress && \
    composer check-platform-reqs && \
    post-build-base && post-build-prod

ENTRYPOINT ["entrypoint-prod"]
CMD ["php-fpm"]

# ======================================================================================================================
# ==============================================  DEVELOPMENT IMAGE  ===================================================
# ======================================================================================================================

FROM base as app-dev

ENV APP_ENV dev
ENV APP_DEBUG 1

USER root

RUN apk --no-cache add git openssh bash && \
    docker-php-ext-enable xdebug

ARG XDEBUG_IDE_KEY

ENV XDEBUG_CLIENT_HOST="host.docker.internal"
ENV XDEBUG_IDE_KEY=${XDEBUG_IDE_KEY}

# ---------------------------------------------------- Scripts ---------------------------------------------------------

COPY docker/app/php/dev-*   $PHP_INI_DIR/conf.d/
COPY docker/app/entrypoint/*-dev  docker/app/post-build/*-dev docker/app/pre-run/*-dev \
     /usr/local/bin/

RUN chmod +x /usr/local/bin/*-dev; \
    mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

USER www-data

ENTRYPOINT ["entrypoint-dev"]
CMD ["php-fpm"]
