FROM php:8.0-cli

USER root

RUN apt-get update && \
    apt-get install -y apt-utils curl gzip

#####################################
# Git:
#####################################
ARG INSTALL_GIT=true
RUN if [ ${INSTALL_GIT} = true ]; then \
    apt-get install -y git \
;fi

#####################################
# Composer:
#####################################
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1

RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer && \
    mkdir /var/www/.composer && chown www-data:www-data -R /var/www/.composer && \
    mkdir /var/www/.config && chown www-data:www-data -R /var/www/.config

#####################################
# PHP Zip:
#####################################
ARG INSTALL_ZIP=false

RUN if [ ${INSTALL_ZIP} = true ]; then \
    apt-get install -y libzip-dev zip unzip \
        && docker-php-ext-install zip \
;fi


#####################################
# PHP GD
#####################################
ARG INSTALL_GD=false

RUN if [ ${INSTALL_GD} = true ]; then \
    apt-get install -y libjpeg-dev libpng-dev libwebp-dev libxpm-dev && \
        docker-php-ext-configure gd \
                   --enable-gd \
                   --with-webp \
                   --with-jpeg \
                   --with-xpm \
        && docker-php-ext-install gd \
;fi

#####################################
# PHP Bcmath:
#####################################
ARG INSTALL_BCMATH=false

RUN if [ ${INSTALL_BCMATH} = true ]; then \
    docker-php-ext-install bcmath \
;fi

#####################################
# PHP Redis:
#####################################
ARG INSTALL_PHPREDIS=false

RUN if [ ${INSTALL_PHPREDIS} = true ]; then \
    pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
;fi

#####################################
# PHP Opcache:
#####################################
ARG INSTALL_OPCACHE=false

RUN if [ ${INSTALL_OPCACHE} = true ]; then \
    docker-php-ext-install opcache \
;fi

#####################################
# PHP Imagick
#####################################
ARG INSTALL_IMAGEMAGICK=false

RUN if [ ${INSTALL_IMAGEMAGICK} = true ]; then \
    apt-get install -y libmagickwand-dev imagemagick \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
;fi

#####################################
# PHP Exif
#####################################
ARG INSTALL_EXIF=false

RUN if [ ${INSTALL_EXIF} = true ]; then \
    docker-php-ext-install exif \
;fi

#####################################
# PHP Pcntl (Required for laravel horizon)
#####################################
ARG INSTALL_PCNTL=false

RUN if [ ${INSTALL_PCNTL} = true ]; then \
    docker-php-ext-install pcntl \
;fi

#####################################
# PHP intl (Internalization support)
#####################################
ARG INSTALL_INTL=false

RUN if [ ${INSTALL_INTL} = true ]; then \
    apt-get install -y libicu-dev \
    && docker-php-ext-install intl \
;fi

#####################################
# PHP soap
#####################################
ARG INSTALL_SOAP=false

RUN if [ ${INSTALL_SOAP} = true ]; then \
    apt-get install -y libxml2-dev \
    && docker-php-ext-install soap \
;fi

###########################################################################
# pgsql
###########################################################################
ARG INSTALL_PGSQL=false

RUN if [ ${INSTALL_PGSQL} = true ]; then \
   apt-get install -y libpq-dev \
   && docker-php-ext-install pgsql \
   && docker-php-ext-install pdo_pgsql \
;fi

#####################################
# PHP PDO Mysql:
#####################################
ARG INSTALL_MYSQL=false

RUN if [ ${INSTALL_MYSQL} = true ]; then \
    docker-php-ext-install pdo_mysql \
        && docker-php-ext-install mysqli \
;fi

#####################################
# PHP gettext
#####################################
ARG INSTALL_GETTEXT=false

RUN if [ ${INSTALL_GETTEXT} = true ]; then \
    docker-php-ext-install gettext \
;fi

#####################################
# PHP sockets
#####################################
ARG INSTALL_SOCKETS=false

RUN if [ ${INSTALL_SOCKETS} = true ]; then \
    docker-php-ext-install sockets \
;fi

###########################################################################
# PHP Memcached:
###########################################################################

ARG INSTALL_MEMCACHED=false

RUN if [ ${INSTALL_MEMCACHED} = true ]; then \
    apt-get install -y libmemcached-dev && \
    pecl -q install memcached && \
    docker-php-ext-enable memcached \
;fi

###########################################################################
# Pecl Sync:
###########################################################################

ARG INSTALL_PECL_SYNC=false

RUN if [ ${INSTALL_PECL_SYNC} = true ]; then \
    pecl -q install sync && \
    docker-php-ext-enable sync \
;fi

#####################################
#  Clean up APT:
#####################################
RUN \
    apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/html
