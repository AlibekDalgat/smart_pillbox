#!/bin/bash
# Ensure Composer dependencies are installed if vendor directory is missing
if [ ! -d "/app/vendor" ]; then
  composer install --no-interaction --optimize-autoloader
fi
# Execute the command passed to the container (default: PHP server)
exec "$@"