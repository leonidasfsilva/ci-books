# Use PHP 8.3 with Apache
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    default-mysql-client \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets (if needed)
RUN npm install && npm run build 2>/dev/null || true

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

# Expose port 80
EXPOSE 80

# Create startup script
RUN echo '#!/bin/bash\n\
# Wait for MySQL to be ready\n\
echo "Waiting for MySQL..."\n\
until mysqladmin ping -h mysql -u root -proot --silent; do\n\
    sleep 2\n\
done\n\
echo "MySQL is ready!"\n\
\n\
# Run database migrations\n\
echo "Running migrations..."\n\
php spark migrate\n\
\n\
# Run database seeds\n\
echo "Running seeds..."\n\
php spark db:seed CreateSampleData\n\
\n\
echo "Starting Apache..."\n\
# Start Apache\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Expose port 80
EXPOSE 80

# Health check
HEALTHCHECK --interval=10s --timeout=5s --start-period=30s --retries=5 \
    CMD curl -f http://localhost/ || exit 1

# Start the application
CMD ["/usr/local/bin/start.sh"]