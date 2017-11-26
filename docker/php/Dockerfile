FROM php:5-fpm-alpine
RUN pear install DB \
    && docker-php-ext-install mysql \
    && apk add --no-cache libxslt-dev \
    && docker-php-ext-install xsl
