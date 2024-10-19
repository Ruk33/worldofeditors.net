FROM php:8.2-apache

# Imagemagic (convert)
# Git (required by composer)
# Build essential/cmake for C++ projects
RUN apt-get update && apt-get install -y \
    git build-essential cmake imagemagick libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# MPQExtractor
RUN git clone https://github.com/Kanma/MPQExtractor.git && \
    cd MPQExtractor && \
    git submodule init && \
    git submodule update && \
    mkdir build && \
    cd build && \
    cmake .. && \
    cmake --build . && \
    mv bin/MPQExtractor /usr/bin/ && \
    chmod +x /usr/bin/MPQExtractor

# BLPConverter
COPY ./BLPConverter /var/www/html/BLPConverter
RUN cd /var/www/html/BLPConverter && \
    mkdir build && \
    cd build && \
    cmake .. && \
    make && \
    mv bin/BLPConverter /usr/bin/ && \
    chmod +x /usr/bin/BLPConverter

# Run composer
COPY ./PHP-MPQ /var/www/html/PHP-MPQ
RUN cd /var/www/html/PHP-MPQ && \
    composer install

RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/ && \
    cp /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/

COPY ./php.ini /usr/local/etc/php/conf.d/

COPY ./ /var/www/html/

EXPOSE 80