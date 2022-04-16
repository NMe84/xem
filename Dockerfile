FROM debian:bullseye-slim

ARG XDEBUG_ENABLED

# Disable Composer nagging inside a container and silence Debian
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1 \
    COMPOSER_NO_INTERACTION=1 \
    DEBIAN_FRONTEND=noninteractive \
    XDEBUG_ENABLED=$XDEBUG_ENABLED

# Install extra repositories and base requirements before doing PHP 8.1 and Node 16
RUN apt-get update --quiet \
    && apt-get install --quiet --yes apt-transport-https apt-utils lsb-release ca-certificates zip unzip git wget curl gnupg cron build-essential g++ vim \
    && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list' \
    && curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update --quiet \
    && apt-get install --quiet --yes \
        php8.1-fpm php8.1-cli php8.1-mbstring php8.1-iconv php8.1-gd php8.1-dom php8.1-exif php8.1-intl \
        php8.1-ldap php8.1-pdo-mysql php8.1-pdo-sqlite php8.1-curl php8.1-mongodb php8.1-redis php8.1-apcu \
        php8.1-pgsql php8.1-bcmath php8.1-phpdbg php8.1-gmp php8.1-xdebug php8.1-zip php8.1-soap php8.1-amqp \
        php8.1-imagick php8.1-phar sassc nodejs yarn \
    && rm -rf /tmp/* /usr/share/doc/* /usr/share/man/* /var/lib/apt/lists/* \
    && mkdir -p /run/php

# Copy stock configs and run script
COPY docker/app/etc/php/ /etc/php
COPY docker/app/run.sh /

# Copy xdebug config, run script will remove it later if we don't need the debugger
COPY docker/php-fpm/conf.d/xdebug.ini /etc/php/8.1/mods-available/xdebug.ini

# Install Symfony, Composer and Node globals
RUN chmod u+x /*.sh \
    && curl -sS https://get.symfony.com/cli/installer | bash - \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    && curl -o /usr/local/bin/composer https://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer \
    && composer self-update --no-progress \
    && npm install -g forever grunt gulp uglifycss uglify-es messageformat webpack \
    && npm cache clear --force

# Make sure the yarn key is accepted
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -

# Fix configuration
RUN sed -i "s/listen = \/run\/php\/php8.1-fpm.sock/listen = 9000/" /etc/php/8.1/fpm/pool.d/www.conf

ADD . /var/www

WORKDIR /var/www

# Make sure the var directory has the correct ownership
RUN mkdir -p var || true && chown -R www-data:www-data var

# Expose FPM port
EXPOSE 9000

# Override CMD
ENTRYPOINT ["/run.sh"]
CMD ["php-fpm8.1", "-F"]
