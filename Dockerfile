FROM php:8.2-apache

RUN apt-get update && apt-get install -y             libzip-dev zip unzip git curl             libonig-dev zlib1g-dev libpng-dev libicu-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html             && chmod -R 755 /var/www/html

WORKDIR /var/www/html/backend
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader; fi

WORKDIR /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
