# Use an official PHP image as a base image
FROM php:8.1-cli

# Install required system dependencies
RUN apt-get update \
    && apt-get install -y zip unzip git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
    
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Set up your working directory
WORKDIR /var/www/html

# Copy only the required files related to Composer first
COPY composer.json /var/www/html/composer.json
COPY composer.lock /var/www/html/composer.lock

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Run Composer to install dependencies
RUN composer install

COPY . .

# Expose the port for the backend service
EXPOSE 8000

# Command to start the PHP server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
