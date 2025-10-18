FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Setup .env file for Docker
RUN cp env .env && \
    sed -i 's|# database\.default\.hostname = localhost|database.default.hostname = mysql|' .env && \
    sed -i 's|# database\.default\.database = ci4|database.default.database = books_management_ci4|' .env && \
    sed -i 's|# database\.default\.username = root|database.default.username = root|' .env && \
    sed -i 's|# database\.default\.password = root|database.default.password = root|' .env && \
    sed -i 's|# database\.default\.DBDriver = MySQLi|database.default.DBDriver = MySQLi|' .env && \
    sed -i 's|# database\.default\.DBPrefix =|database.default.DBPrefix =|' .env && \
    sed -i 's|# database\.default\.port = 3306|database.default.port = 3306|' .env

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create writable directories and set permissions
RUN mkdir -p writable/cache writable/debugbar writable/logs writable/session writable/uploads \
    && chown -R www-data:www-data writable \
    && chmod -R 755 writable

# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

EXPOSE 80