#Use phusion/baseimage as base image. To make your builds reproducible, make
# sure you lock down to a specific version, not to `latest`!
# See https://github.com/phusion/baseimage-docker/blob/master/Changelog.md for
# a list of version numbers.
FROM phusion/baseimage:master
USER root
MAINTAINER jackyyin
EXPOSE 80 443

# ...put your own build instructions here...
RUN apt-get update \
    && apt-get install -y locales  \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8
ENV HOME /root

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    git \
    netcat \
    nginx \
    software-properties-common \
    sudo \
    unzip\
    vim \
    wget \
    build-essential \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.3 \
    && apt-get install -y --no-install-recommends \
    php-pear \
    php7.3-gd \
    php7.3-mbstring \
    php7.3-mysql \
    php7.3-xml \
    php7.3-dev \
    && ( \
      cd /tmp \
      && mkdir librdkafka \
      && cd librdkafka \
      && git clone https://github.com/edenhill/librdkafka.git . \
      && ./configure \
      && make \
      && make install \
    ) \
    && pecl install redis \
    rdkafka \
    swoole \ 
    && apt-get remove -y --purge software-properties-common

# configurations
COPY ./config/docker/nginx.conf     /etc/nginx/nginx.conf
COPY ./config/docker/laravels.conf  /etc/nginx/conf.d/laravels.conf
COPY ./config/docker/php.ini     /etc/php/7.3/cli/php.ini
COPY ./config/docker/redis.ini   /etc/php/7.3/mods-available/redis.ini
COPY ./config/docker/rdkafka.ini /etc/php/7.3/mods-available/rdkafka.ini
COPY ./config/docker/swoole.ini  /etc/php/7.3/mods-available/swoole.ini

RUN ln -s /etc/php/7.3/mods-available/redis.ini 20-redis.ini \
    && ln -s /etc/php/7.3/mods-available/rdkafka.ini 20-rdkafka.ini \
    && ln -s /etc/php/7.3/mods-available/swoole.ini 20-swoole.ini \
    && phpenmod redis rdkafka swoole

# Use baseimage-docker's init system.
CMD ["/sbin/my_init"]

### Additional Process ###

# Adding additional nginx process
RUN mkdir /etc/service/nginx
COPY ./config/docker/service/nginx.sh /etc/service/nginx/run
RUN chmod +x /etc/service/nginx/run

# Adding additional swoole process
RUN mkdir /etc/service/swoole
COPY ./config/docker/service/swoole.sh /etc/service/swoole/run
RUN chmod +x /etc/service/swoole/run

# Adding additional queue-worker process
RUN mkdir /etc/service/queue-worker
COPY ./config/docker/service/queue-worker.sh /etc/service/queue-worker/run
RUN chmod +x /etc/service/queue-worker/run

### First Level Startup Process ###

# Change File Permission

### Second Level Startup Process ###

# Laravel

WORKDIR /var/www/html

# composer
COPY composer.json .
COPY composer.lock .
RUN wget https://getcomposer.org/composer.phar -O /usr/local/bin/composer \
    && chmod 755 /usr/local/bin/composer \
    && composer install --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html
# Clean up APT when done.
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
