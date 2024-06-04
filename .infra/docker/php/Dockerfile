FROM php:8.2-fpm-bullseye
RUN usermod -u 1000 www-data

WORKDIR "/app"

ADD . /app

RUN apt-get update \
 && apt-get -y --no-install-recommends install \
    g++ \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    git \
    unzip \
    zlib1g-dev \
    openssh-client \
    openssl \
    libxslt1-dev \
  && apt-get clean

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-configure intl
RUN docker-php-ext-install exif \
    gd \
    intl \
    mbstring \
    pdo \
    pdo_mysql \
    soap \
    zip \
    calendar \
    xsl
RUN pecl install \
    apcu-5.1.21
RUN docker-php-ext-enable \
    apcu \
    opcache

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir /usr/local/bin --filename composer --2
RUN composer --version

COPY .infra/docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

RUN chown -R www-data:www-data /app

CMD ["php-fpm"]
