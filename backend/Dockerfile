FROM php:8.3-apache

WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libcurl4-openssl-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql curl

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy composer.json only
COPY composer.json ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader

# Copy application code
COPY . .

# Generate autoloader
RUN composer dump-autoload --optimize

# Configure Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Create public directory
RUN mkdir -p /var/www/html/public

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80 