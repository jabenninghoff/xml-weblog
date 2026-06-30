FROM php:5.6.40-fpm-alpine@sha256:e3845c650c700234be3fb5b94865753d1a4534f8820d4dea1d0ee6d875efe02b

RUN pear install DB \
    && apk add --no-cache libxslt libxslt-dev \
    && docker-php-ext-install mysql xsl \
    && apk del libxslt-dev
