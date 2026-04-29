#!/bin/sh
set -e

if [ -f /var/www/html/yii ]; then
    php /var/www/html/yii migrate/up --interactive=0
fi

exec "$@"
