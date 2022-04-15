FROM php:8.1-fpm

ADD . /var/www

WORKDIR /var/www

# Make sure the yarn key is accepted
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -

# Add and enable all relevant extensions
RUN docker-php-ext-install bcmath curl gd iconv mysql
RUN docker-php-ext-enable bcmath curl gd iconv mysql

# Enable XDebug if we are on the dev environment
RUN chmod u+x ./bin/enable-xdebug-docker.sh && ./bin/enable-xdebug-docker.sh

# Fix configuration
RUN sed -i "s/listen = \/run\/php\/php8.1-fpm.sock/listen = 9000/" /etc/php/8.1/fpm/pool.d/www.conf

# Make sure the var directory has the correct ownership
RUN mkdir -p var || true && chown -R www-data:www-data var

CMD ["php-fpm8.1", "-F"]
