FROM gcr.io/google-appengine/php72

ARG ENV_JWT_PASSPHRASE

ENV DOCUMENT_ROOT='/app/public' FRONT_CONTROLLER_FILE='index.php' APP_ENV='prod' SYMFONY_ENV='prod' DATABASE_URL='mysql://root:1mISWg90@localhost/hdyf_db?serverVersion=5.7&unix_socket=/cloudsql/m-app-3:us-central1:m-app-3' CORS_ALLOW_ORIGIN='^https?:\/\/hdyf-(.*)-dot-m-app-3\.appspot\.com$' JWT_PASSPHRASE='$ENV_JWT_PASSPHRASE' COMPOSER_FLAGS='--no-dev --prefer-dist' DETECTED_PHP_VERSION='7.2'

COPY . $APP_DIR
RUN chown -R www-data.www-data $APP_DIR
RUN /build-scripts/composer.sh

RUN /bin/bash /build-scripts/move-config-files.sh
RUN /usr/sbin/nginx -t -c /etc/nginx/nginx.conf
RUN /bin/bash /build-scripts/lockdown.sh
