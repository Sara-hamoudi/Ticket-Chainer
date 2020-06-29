FROM php:7.2.3-fpm

ARG UID
ARG GID

##<romaricp>##
RUN apt-get update \
    && apt-get install -y --no-install-recommends vim curl debconf subversion git apt-transport-https apt-utils \
    build-essential locales acl mailutils wget zlib1g-dev libicu-dev g++ zip unzip \
    gnupg gnupg1 gnupg2

RUN docker-php-ext-install zip

# Install intl ext
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Install and configure php-redis
RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/4.1.1.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

COPY php.ini /etc/php/7.2.3/php.ini
COPY php-fpm-pool.conf /etc/php/7.2.3/pool.d/www.conf

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
	mv composer.phar /usr/local/bin/composer

RUN wget --no-check-certificate https://phar.phpunit.de/phpunit-6.5.3.phar && \
    mv phpunit*.phar phpunit.phar && \
    chmod +x phpunit.phar && \
    mv phpunit.phar /usr/local/bin/phpunit


# Install Node.js 8 and npm 5 and yarn
RUN	curl -sL https://deb.nodesource.com/setup_8.x | bash && \
    apt-get install -y nodejs  && \
    npm install -g yarn

RUN groupadd -g ${GID} nonroot && \
    useradd -r -u ${UID}  -g nonroot -d /home/nonroot -s /sbin/nologin nonroot && \
    mkdir /home/nonroot && \
    chown -R nonroot:nonroot /home/nonroot


# Bash aliases
RUN echo 'alias ll="ls -alF"' >> /home/nonroot/.bashrc  && \
    echo 'alias sf="./bin/console"' >> /home/nonroot/.bashrc


RUN rm -rf /var/lib/apt/lists/*
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

WORKDIR /var/www
##</romaricp>##

USER nonroot
EXPOSE 9000
CMD ["php-fpm"]
