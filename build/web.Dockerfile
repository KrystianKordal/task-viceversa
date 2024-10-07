FROM php:8.3-apache
WORKDIR /var/www/task-viceversa
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
