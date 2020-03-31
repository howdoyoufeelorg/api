FROM gcr.io/google-appengine/php72

ENV DOCUMENT_ROOT /app/public
ENV SKIP_LOCKDOWN_DOCUMENT_ROOT true

# Allow customizing some composer flags
ARG COMPOSER_FLAGS='--no-scripts --no-dev --prefer-dist'
ENV COMPOSER_FLAGS=${COMPOSER_FLAGS}

# Here's some additional config to have xdebug
# Keep in mind that you need to ACTIVATE xdebug in php.ini in root of the project
RUN apt-get update -y && \
    apt-get -y upgrade && \
    apt-get install -y --no-install-recommends \
    autoconf \
    gcc && \
    /bin/bash /build-scripts/apt-cleanup.sh

# XDEBUG - turn it on if needed. Also uncomment lines in php.ini in root dir.
#RUN pecl install xdebug

COPY bin/ /app/bin/
COPY config/ /app/config/
COPY public/ /app/public/
COPY src/ /app/src/
COPY templates/ /app/templates/
COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock
COPY symfony.lock /app/symfony.lock
COPY php.ini /app/php.ini
COPY .env /app/.env

RUN mkdir /app/var
RUN mkdir /app/var/cache

RUN chown -R www-data /app

RUN /build-scripts/composer.sh

# SUPERVISORD additional config
COPY additional-supervisord.conf /etc/supervisor/conf.d/additional-supervisord.conf

# CUSTOM ENTRYPOINT (will also trigger the default Google entrypoint
RUN mkdir /additional-build-scripts
COPY entrypoint.sh /additional-build-scripts/entrypoint.sh
RUN chown -R www-data /additional-build-scripts

#ENTRYPOINT ["/build-scripts/entrypoint.sh"]
ENTRYPOINT ["/additional-build-scripts/entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

