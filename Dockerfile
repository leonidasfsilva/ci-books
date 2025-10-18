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
RUN if [ -f .env ]; then \
        echo "Using existing .env file"; \
        # Ensure Docker-specific settings \
        sed -i 's|database\.default\.hostname = .*|database.default.hostname = mysql|' .env; \
        sed -i 's|app\.baseURL = .*|app.baseURL = '\''http://localhost:8080/'\''|' .env; \
        sed -i 's|cache\.handler = .*|cache.handler = dummy|' .env; \
    else \
        echo "Creating .env from template"; \
        cp env .env && \
        sed -i 's|app\.baseURL = '\''https://ci-books\.localhost/'\''|app.baseURL = '\''http://localhost:8080/'\''|' .env && \
        sed -i 's|# database\.default\.hostname = localhost|database.default.hostname = mysql|' .env && \
        sed -i 's|# database\.default\.database = ci4|database.default.database = books_management_ci4|' .env && \
        sed -i 's|# database\.default\.username = root|database.default.username = root|' .env && \
        sed -i 's|# database\.default\.password = root|database.default.password = root|' .env && \
        sed -i 's|# database\.default\.DBDriver = MySQLi|database.default.DBDriver = MySQLi|' .env && \
        sed -i 's|# database\.default\.DBPrefix =|database.default.DBPrefix =|' .env && \
        sed -i 's|# database\.default\.port = 3306|database.default.port = 3306|' .env && \
        echo -e "\n# Cache\ncache.handler = file" >> .env; \
    fi

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

# Define volumes for Docker Compose compatibility
VOLUME ["/var/www/html/writable"]

# Create startup script
RUN echo '#!/bin/bash\n\
# Fix writable permissions after volume mount\n\
chown -R www-data:www-data writable\n\
chmod -R 755 writable\n\
\n\
# Run database migrations\n\
php spark migrate\n\
\n\
# Run database seeds\n\
php spark db:seed CreateSampleData\n\
\n\
# Start Apache\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Start the application
CMD ["/usr/local/bin/start.sh"]