#!/bin/bash

if [ ! -d "/app/vendor" ]; then
  composer install --no-interaction --optimize-autoloader
fi
service cron start
exec "$@"