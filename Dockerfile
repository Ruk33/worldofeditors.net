FROM php:8.2-apache

# Imagemagic (convert)
# Git (required by composer)
# Lib C++ required by MPQExtractor
RUN apt-get update && apt-get install -y \
    git build-essential cmake imagemagick libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# MPQExtractor
RUN git clone https://github.com/Kanma/MPQExtractor.git
RUN cd MPQExtractor && git submodule init && git submodule update && mkdir build && cd build && cmake .. && cmake --build . && cd ../..

COPY ./ /var/www/html/

COPY MPQExtractor/build/bin/MPQExtractor /var/www/html/PHP-MPQ/MPQExtractor
# Allow MPQExtractor to be executed
RUN chmod +x /var/www/html/PHP-MPQ/MPQExtractor

EXPOSE 80

# Run composer
RUN cd /var/www/html/PHP-MPQ && composer install

RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/ && cp /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/
