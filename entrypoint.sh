#!/usr/bin/env bash

# Execute the default entrypoint (sets up NGINX, executes composer etc)
/build-scripts/entrypoint.sh

cd /app
bin/console doctrine:schema:update --force --no-interaction

exec "$@"