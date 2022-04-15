#!/usr/bin/bash

if [ "${APP_ENV}" = "dev" ]; then
  docker-php-ext-install xdebug
  docker-php-ext-enable xdebug
fi