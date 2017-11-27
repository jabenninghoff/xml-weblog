FROM php:5-fpm-alpine
RUN pear install DB \
    && apk add --no-cache libxslt libxslt-dev \
    && docker-php-ext-install mysql xsl \
    && apk del pkgconf pkgconfig zlib-dev libxml2-dev libxslt-dev
