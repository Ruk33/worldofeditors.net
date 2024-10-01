FROM php:8.2-apache
COPY ./ /var/www/html/
EXPOSE 80
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/ && cp /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/
