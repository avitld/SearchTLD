# Use PHP 8.2 as base image
FROM php:8.2-fpm

# Set working directory inside the container
WORKDIR /var/www/

# Copy project files to the working directory in the container
COPY ./php /var/www/searchtld

# Install Nginx and other necessary packages
RUN apt-get update \
    && apt-get install -y nginx supervisor libcurl4-openssl-dev libxml2-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install curl xml

# Copy Nginx and PHP-FPM configurations and project files
COPY ./docker/nginx.conf /etc/nginx/conf.d/
RUN rm /etc/nginx/sites-enabled/default
RUN rm /etc/nginx/sites-available/default
# Expose ports
EXPOSE 80

# Start services
#CMD service nginx start && php-fpm
#CMD ["nginx", "-g", "daemon off;"]

# Copy Supervisor configuration for services
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Disable PHP Warnings
COPY ./docker/php.ini /usr/local/etc/php/php_errors.ini

# Start Supervisor
CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
