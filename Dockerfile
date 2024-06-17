# Use the official PHP image as the base image
FROM php:7.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    nano \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy the Laravel application
COPY . .

# Install Laravel dependencies
RUN composer install --no-scripts --no-autoloader

# Generate the autoload files
RUN composer dump-autoload

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 777 storage/
RUN chmod -R 777 bootstrap/
RUN chmod -R 777 resources/
RUN chmod -R 777 public/

#Run Migrations
RUN php artisan migrate
RUN php artisan seed

# Clear caches
RUN php artisan optimize:clear

# Generate key
RUN php artisan key:generate

CMD php artisan serve --host=0.0.0.0 --port=8080
EXPOSE 8080
