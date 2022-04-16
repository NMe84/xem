#!/bin/sh
set -e

#if [ "${XDEBUG_ENABLED:=0}" != "1" ]; then
#    rm -f /etc/php/8.1/mods-available/xdebug.ini
#fi

exec "$@"
