FROM php:5-fpm-alpine
RUN pear install DB \
    && docker-php-ext-install mysql \
    && docker-php-ext-install php_xsl
