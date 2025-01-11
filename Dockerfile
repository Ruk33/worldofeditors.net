FROM serversideup/php:8.2-fpm-nginx

ENV PHP_MAX_EXECUTION_TIME=600
ENV PHP_MAX_INPUT_TIME=600
ENV PHP_MEMORY_LIMIT=1G
ENV PHP_POST_MAX_SIZE=1G
ENV PHP_UPLOAD_MAX_FILE_SIZE=1G
ENV UNIT_MAX_BODY_SIZE=1073741824

USER root

# Update and install required dependencies
RUN apt-get update && apt-get install -y \
    git build-essential cmake imagemagick libmagickwand-dev --no-install-recommends && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# MPQExtractor
RUN git clone https://github.com/Ruk33/MPQExtractor.git && \
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
RUN git clone https://github.com/Ruk33/BLPConverter.git && \
    cd BLPConverter && \
    mkdir build && \
    cd build && \
    cmake .. && \
    make && \
    mv bin/BLPConverter /usr/bin/ && \
    chmod +x /usr/bin/BLPConverter

USER www-data

COPY ./ /var/www/html/
