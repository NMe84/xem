FROM php:8.1-fpm

ADD . /var/www

WORKDIR /var/www

# Install some tools that will be useful
RUN apt-get update && apt-get install -y curl gnupg libjpeg-dev libcurl4-openssl-dev libpng-dev libxml2-dev vim zlib1g-dev

# Make sure the yarn key is accepted
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -

# Add and enable all relevant extensions
RUN docker-php-ext-install bcmath gd mysqli

# Enable XDebug if we are on the dev environment
RUN chmod u+x /var/www/bin/docker-enable-xdebug.sh && /var/www/bin/docker-enable-xdebug.sh

# Fix configuration
RUN sed -i "s/listen = \/run\/php\/php8.1-fpm.sock/listen = 9000/" /etc/php/8.1/fpm/pool.d/www.conf

# Make sure the var directory has the correct ownership
RUN mkdir -p var || true && chown -R www-data:www-data var

CMD ["php-fpm8.1", "-F"]
