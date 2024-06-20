FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

#COPY . .

#RUN chown -R www-data:www-data /var/www/html
