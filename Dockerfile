FROM php:8.2-apache

# Imagemagic (convert)
RUN apt-get update && apt-get install -y \
    git imagemagick libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./ /var/www/html/
EXPOSE 80

# Run composer
RUN cd /var/www/html/PHP-MPQ && composer install

# Allow MPQExtractor to be executed
RUN chmod +x /var/www/html/PHP-MPQ/MPQExtractor

RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/ && cp /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/
