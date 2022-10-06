FROM php:8.1.0-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git

# Install PHP extensions
RUN apt-get install -y libpng-dev libjpeg-dev libgif-dev zlib1g-dev libwebp-dev
RUN apt-get update
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure gd --enable-gd --with-jpeg --with-webp
RUN docker-php-ext-install gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# SET right rights in working dir
RUN chown -R www-data:www-data /var/www
