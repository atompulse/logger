FROM phpdockerio/php:8.2-cli
WORKDIR "/app"

RUN apt-get update && apt-get -y --no-install-recommends install mc nano git

RUN apt-get -y install software-properties-common apt-utils

# Install selected extensions and other dependent stuff
RUN apt-get -y --no-install-recommends --allow-unauthenticated install  \
    php8.2-ast \
    php8.2-bcmath \
    php8.2-decimal \
    php8.2-ds \
    php8.2-intl \
    php8.2-memcache \
    php8.2-memcached \
    php8.2-msgpack \
    php8.2-raphf \
    php8.2-uuid \
    php8.2-xhprof \
    php8.2-xsl \
    php8.2-yaml

# PECL custom extensions requirements
RUN add-apt-repository ppa:ondrej/php -y && apt-get update && apt-get -y install php8.2-dev --allow-unauthenticated && apt-get -y install librabbitmq-dev
RUN mkdir -p /tmp/pear/cache && mkdir -p /tmp/pear/temp
RUN pecl channel-update pecl.php.net && pecl config-set php_ini /etc/php/8.2/cli/php.ini

# Cleanup stuff to save space
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*