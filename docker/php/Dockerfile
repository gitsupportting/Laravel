FROM php:7.4-fpm

LABEL php="7.4"

ARG BUILD_DATE
ARG BUILD_VERSION

LABEL build_date=$BUILD_DATE
LABEL build_version=$BUILD_VERSION

ENV IMAGE_USER=php
ENV HOME=/home/$IMAGE_USER
ENV COMPOSER_HOME=$HOME/.composer
ENV BIN_DIR=/usr/local/bin

USER root

# Base setup for user
RUN adduser --disabled-password --gecos "" $IMAGE_USER \
    && echo "$IMAGE_USER  ALL = ( ALL ) NOPASSWD: ALL" >> /etc/sudoers

RUN apt-get -y update \
    && apt-get upgrade -yqq \
    && apt-get -y install --no-install-recommends \
    apt-transport-https \
    apt-utils \
    ca-certificates \
    && DEBIAN_FRONTEND=noninteractive apt-get install -yqq \
    curl \
    imagemagick \
    libzip-dev \
    libbz2-dev \
    libcurl4-gnutls-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libicu-dev \
    libedit-dev \
    libzip-dev \
    libonig-dev \
    libssl-dev \
    libuv1-dev \
    libxml2-dev \
    libxslt1-dev \
    libxslt1.1 \
    locales \
    openssl \
    unzip \
    zip \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install php extensions
RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    bz2 \
    calendar \
    exif \
    gd \
    iconv \
    curl \
    json \
    mbstring \
    mysqli \
    opcache \
    pdo_mysql \
    intl \
    readline \
    simplexml \
    soap \
    xml \
    xmlrpc \
    zip

# Install imagick
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Some settings
RUN { \
    echo "memory_limit=1024M"; \
    echo "date.timezone=Europe/Berlin"; \
} > /usr/local/etc/php/conf.d/zz-conf.ini

RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=1'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.memory_consumption=192'; \
    echo 'opcache.max_wasted_percentage=10'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# PHP COMPOSER
RUN curl --silent --show-error https://getcomposer.org/installer | php \
   && mv composer.phar /usr/local/bin/composer \
   && composer global require "hirak/prestissimo:^0.3" \
   && rm -rf ~/.composer/cache/* \
   && chown -R $IMAGE_USER:$IMAGE_USER ~/.composer

# Install php copy paste detector
RUN curl -sLo $BIN_DIR/phpcpd https://phar.phpunit.de/phpcpd.phar \
    && chmod +x $BIN_DIR/phpcpd

# Install php code sniffer
RUN curl -sLo $BIN_DIR/phpcs https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar \
    && chmod +x $BIN_DIR/phpcs

# Install sensiolabs security checker
RUN curl -sLo $BIN_DIR/security-checker https://get.sensiolabs.org/security-checker.phar \
    && chmod +x $BIN_DIR/security-checker

# Install cachetool
RUN curl -sLo $BIN_DIR/cachetool http://gordalina.github.io/cachetool/downloads/cachetool.phar \
    && chmod +x $BIN_DIR/cachetool

# Cleanup
RUN apt-get purge -yqq --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false \
    apt-utils \
    build-essential \
    dpkg-dev \
    file \
    libc-dev \
    libpcre3-dev \
    pkg-config \
    re2c

RUN apt-get autoremove -yqq --purge \
    && apt-get autoclean -yqq \
    && apt-get clean \
    && rm -rf /var/cache/apt/ /var/lib/apt/lists/* /var/log/* /tmp/* /var/tmp/* /usr/share/doc /usr/share/doc-base /usr/share/groff/* /usr/share/info/* /usr/share/linda/* /usr/share/lintian/overrides/* /usr/share/locale/* /usr/share/man/* /usr/share/locale/* /usr/share/gnome/help/*/* /usr/share/doc/kde/HTML/*/* /usr/share/omf/*/*-*.emf

USER $IMAGE_USER
