FROM php:8.3-apache
WORKDIR /var/www/task-viceversa
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
